<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use AppBundle\Entity\CommentSharedExperience;

/**
 * CommentSharedExperience controller.
 *
 * @Route("/commentsharedexperience")
 */
class CommentSharedExperienceController extends Controller
{
    /**
     * Lists all CommentSharedExperience entities.
     *
     * @Route("/", name="commentsharedexperience")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:CommentSharedExperience')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($commentSharedExperiences, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('@Admin/commentsharedexperience/index.html.twig', array(
            'commentSharedExperiences' => $commentSharedExperiences,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,

        ));
    }

    /**
     * Create filter form and process filter request.
     *
     */
    protected function filter($queryBuilder, Request $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('AdminBundle\Form\CommentSharedExperienceFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('CommentSharedExperienceControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->handleRequest($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('CommentSharedExperienceControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('CommentSharedExperienceControllerFilter')) {
                $filterData = $session->get('CommentSharedExperienceControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('AdminBundle\Form\CommentSharedExperienceFilterType', $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }


    /**
     * Get results from paginator and get paginator view.
     *
     */
    protected function paginator($queryBuilder, Request $request)
    {
        //sorting
        $sortCol = $queryBuilder->getRootAlias() . '.' . $request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'desc'));
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($request->get('pcg_show', 10));

        try {
            $pagerfanta->setCurrentPage($request->get('pcg_page', 1));
        } catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }

        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function ($page) use ($me, $request) {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;
            return $me->generateUrl('commentsharedexperience', $requestParams);
        };

        // Paginator - view
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => 'previous',
            'next_message' => 'next',
        ));

        return array($entities, $pagerHtml);
    }


    /*
     * Calculates the total of records string
     */
    protected function getTotalOfRecordsString($queryBuilder, $request)
    {
        $totalOfRecords = $queryBuilder->select('COUNT(e.id)')->getQuery()->getSingleScalarResult();
        $show = $request->get('pcg_show', 10);
        $page = $request->get('pcg_page', 1);

        $startRecord = ($show * ($page - 1)) + 1;
        $endRecord = $show * $page;

        if ($endRecord > $totalOfRecords) {
            $endRecord = $totalOfRecords;
        }
        return "Showing $startRecord - $endRecord of $totalOfRecords Records.";
    }


    /**
     * Displays a form to create a new CommentSharedExperience entity.
     *
     * @Route("/new", name="commentsharedexperience_new")
     * @Method({"GET", "POST"})
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAction(Request $request)
    {

        $commentSharedExperience = new CommentSharedExperience();
        $form = $this->createForm('AdminBundle\Form\CommentSharedExperienceType', $commentSharedExperience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$shared = $em->getRepository('AppBundle:SharedExperience')->find($id);
            //$commentSharedExperience->setSharedExperience($shared);
            $commentSharedExperience->setUser($this->getUser());
            $commentSharedExperience->setLikes(0);
            $em->persist($commentSharedExperience);
            $em->flush();


            $manager = $this->get('mgilet.notification');
            $notif = $manager->createNotification($commentSharedExperience->getUser()->getUsername() . ' has commented a post');
            $notif->setMessage($commentSharedExperience->getSharedExperience()->getTitle());
            $notif->setLink('http://symfony.com/');
            // or the one-line method :
            // $manager->createNotification('Notification subject','Some random text','http://google.fr');

            // you can add a notification to a list of entities
            // the third parameter ``$flush`` allows you to directly flush the entities
            $manager->addNotification(array($this->getUser()), $notif, true);

            $editLink = $this->generateUrl('commentsharedexperience_edit', array('id' => $commentSharedExperience->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New commentSharedExperience was created successfully.</a>");

            $nextAction = $request->get('submit') == 'save' ? 'commentsharedexperience' : 'commentsharedexperience_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('@Admin/commentsharedexperience/new.html.twig', array(
            'commentSharedExperience' => $commentSharedExperience,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a CommentSharedExperience entity.
     *
     * @Route("/{id}", name="commentsharedexperience_show")
     * @Method("GET")
     */
    public function showAction(CommentSharedExperience $commentSharedExperience)
    {
        $deleteForm = $this->createDeleteForm($commentSharedExperience);
        return $this->render('@Admin/commentsharedexperience/show.html.twig', array(
            'commentSharedExperience' => $commentSharedExperience,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing CommentSharedExperience entity.
     *
     * @Route("/{id}/edit", name="commentsharedexperience_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CommentSharedExperience $commentSharedExperience)
    {
        $deleteForm = $this->createDeleteForm($commentSharedExperience);
        $editForm = $this->createForm('AdminBundle\Form\CommentSharedExperienceType', $commentSharedExperience);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentSharedExperience);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('commentsharedexperience_edit', array('id' => $commentSharedExperience->getId()));
        }
        return $this->render('@Admin/commentsharedexperience/edit.html.twig', array(
            'commentSharedExperience' => $commentSharedExperience,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a CommentSharedExperience entity.
     *
     * @Route("/{id}", name="commentsharedexperience_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CommentSharedExperience $commentSharedExperience)
    {

        $form = $this->createDeleteForm($commentSharedExperience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentSharedExperience);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The CommentSharedExperience was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the CommentSharedExperience');
        }

        return $this->redirectToRoute('commentsharedexperience');
    }

    /**
     * Creates a form to delete a CommentSharedExperience entity.
     *
     * @param CommentSharedExperience $commentSharedExperience The CommentSharedExperience entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CommentSharedExperience $commentSharedExperience)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentsharedexperience_delete', array('id' => $commentSharedExperience->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Delete CommentSharedExperience by id
     *
     * @Route("/delete/{id}", name="commentsharedexperience_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(CommentSharedExperience $commentSharedExperience)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($commentSharedExperience);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The CommentSharedExperience was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the CommentSharedExperience');
        }

        return $this->redirect($this->generateUrl('commentsharedexperience'));

    }


    /**
     * Bulk Action
     * @Route("/bulk-action/", name="commentsharedexperience_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:CommentSharedExperience');

                foreach ($ids as $id) {
                    $commentSharedExperience = $repository->find($id);
                    $em->remove($commentSharedExperience);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'commentSharedExperiences was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the commentSharedExperiences ');
            }
        }

        return $this->redirect($this->generateUrl('commentsharedexperience'));
    }


}
