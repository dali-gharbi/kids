<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use AppBundle\Entity\SharedExperience;

/**
 * SharedExperience controller.
 *
 * @Route("/sharedexperiences")
 */
class SharedExperienceController extends Controller
{
    /**
     * Lists all SharedExperience entities.
     *
     * @Route("/", name="sharedexperiences")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:SharedExperience')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($sharedExperiences, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('@Admin/sharedexperience/index.html.twig', array(
            'sharedExperiences' => $sharedExperiences,
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
        $filterForm = $this->createForm('AdminBundle\Form\SharedExperienceFilterType');


        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('SharedExperienceControllerFilter');
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
                $session->set('SharedExperienceControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('SharedExperienceControllerFilter')) {
                $filterData = $session->get('SharedExperienceControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('AdminBundle\Form\SharedExperienceFilterType', $filterData);
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
            return $me->generateUrl('sharedexperience', $requestParams);
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
     * Displays a form to create a new SharedExperience entity.
     *
     * @Route("/new", name="sharedexperiences_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $sharedExperience = new SharedExperience();
        $form = $this->createForm('AdminBundle\Form\SharedExperienceType', $sharedExperience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $sharedExperience->setLikes(0);
            $sharedExperience->setUser($this->getUser());
            $em->persist($sharedExperience);
            $em->flush();

            $editLink = $this->generateUrl('sharedexperiences', array('id' => $sharedExperience->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New sharedExperience was created successfully.</a>");

            $nextAction = $request->get('submit') == 'save' ? 'sharedexperiences' : 'sharedexperiences_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('@Admin/sharedexperience/new.html.twig', array(
            'sharedExperience' => $sharedExperience,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a SharedExperience entity.
     *
     * @Route("/{id}", name="sharedexperiences_show")
     * @Method("GET")
     */
    public function showAction(SharedExperience $sharedExperience)
    {
        $deleteForm = $this->createDeleteForm($sharedExperience);
        return $this->render('@Admin/sharedexperience/show.html.twig', array(
            'sharedExperience' => $sharedExperience,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing SharedExperience entity.
     *
     * @Route("/{id}/edit", name="sharedexperiences_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SharedExperience $sharedExperience)
    {
        $deleteForm = $this->createDeleteForm($sharedExperience);
        $editForm = $this->createForm('AdminBundle\Form\SharedExperienceType', $sharedExperience);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sharedExperience);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('sharedexperiences_edit', array('id' => $sharedExperience->getId()));
        }
        return $this->render('@Admin/sharedexperience/edit.html.twig', array(
            'sharedExperience' => $sharedExperience,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a SharedExperience entity.
     *
     * @Route("/{id}", name="sharedexperiences_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SharedExperience $sharedExperience)
    {

        $form = $this->createDeleteForm($sharedExperience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sharedExperience);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The SharedExperience was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the SharedExperience');
        }

        return $this->redirectToRoute('sharedexperiences');
    }

    /**
     * Creates a form to delete a SharedExperience entity.
     *
     * @param SharedExperience $sharedExperience The SharedExperience entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SharedExperience $sharedExperience)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sharedexperience_delete', array('id' => $sharedExperience->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Delete SharedExperience by id
     *
     * @Route("/delete/{id}", name="sharedexperiences_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(SharedExperience $sharedExperience)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($sharedExperience);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The SharedExperience was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the SharedExperience');
        }

        return $this->redirect($this->generateUrl('sharedexperiences'));

    }


    /**
     * Bulk Action
     * @Route("/bulk-action/", name="sharedexperiences_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:SharedExperience');

                foreach ($ids as $id) {
                    $sharedExperience = $repository->find($id);
                    $em->remove($sharedExperience);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'sharedExperiences was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the sharedExperiences ');
            }
        }

        return $this->redirect($this->generateUrl('sharedexperiences'));
    }

    /**
     * Lists all SharedExperience entities.
     *
     * @Route("/{id}/{title}/search/sharedexperiences", name="searchSharedExperiences")
     * @Method("GET")
     */
    public function searchAction(Request $request, $id, $title)
    {
        $em = $this->getDoctrine()->getManager();
        if ($title == "")
            $queryBuilder = $em->getRepository('AppBundle:SharedExperience')->createQueryBuilder('e')->andWhere('e.theme=' . $id);
        else
            $queryBuilder = $em->getRepository('AppBundle:SharedExperience')->createQueryBuilder('e')->andWhere('e.theme=' . $id)->andWhere("e.title like '%" . $title . "%'");

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($sharedExperiences, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('@AdminBundle:sharedexperience:search.twig', array(
            'sharedExperiences' => $sharedExperiences,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,
            'id' => $id
        ));
    }
}
