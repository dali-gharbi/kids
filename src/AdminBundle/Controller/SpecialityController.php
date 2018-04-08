<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use AppBundle\Entity\Speciality;

/**
 * Speciality controller.
 *
 * @Route("/speciality")
 */
class SpecialityController extends Controller
{
    /**
     * Lists all Speciality entities.
     *
     * @Route("/", name="speciality")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Speciality')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($specialities, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('@Admin/speciality/index.html.twig', array(
            'specialities' => $specialities,
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
        $filterForm = $this->createForm('AdminBundle\Form\SpecialityFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('SpecialityControllerFilter');
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
                $session->set('SpecialityControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('SpecialityControllerFilter')) {
                $filterData = $session->get('SpecialityControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('AdminBundle\Form\SpecialityFilterType', $filterData);
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
            return $me->generateUrl('speciality', $requestParams);
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
     * Displays a form to create a new Speciality entity.
     *
     * @Route("/new", name="speciality_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $speciality = new Speciality();
        $form = $this->createForm('AdminBundle\Form\SpecialityType', $speciality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($speciality);
            $em->flush();

            $editLink = $this->generateUrl('speciality_edit', array('id' => $speciality->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New speciality was created successfully.</a>");

            $nextAction = $request->get('submit') == 'save' ? 'speciality' : 'speciality_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('@Admin/speciality/new.html.twig', array(
            'speciality' => $speciality,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a Speciality entity.
     *
     * @Route("/{id}", name="speciality_show")
     * @Method("GET")
     */
    public function showAction(Speciality $speciality)
    {
        $deleteForm = $this->createDeleteForm($speciality);
        return $this->render('@Admin/speciality/show.html.twig', array(
            'speciality' => $speciality,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing Speciality entity.
     *
     * @Route("/{id}/edit", name="speciality_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Speciality $speciality)
    {
        $deleteForm = $this->createDeleteForm($speciality);
        $editForm = $this->createForm('AdminBundle\Form\SpecialityType', $speciality);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($speciality);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('speciality_edit', array('id' => $speciality->getId()));
        }
        return $this->render('@Admin/speciality/edit.html.twig', array(
            'speciality' => $speciality,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a Speciality entity.
     *
     * @Route("/{id}", name="speciality_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Speciality $speciality)
    {

        $form = $this->createDeleteForm($speciality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($speciality);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Speciality was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Speciality');
        }

        return $this->redirectToRoute('speciality');
    }

    /**
     * Creates a form to delete a Speciality entity.
     *
     * @param Speciality $speciality The Speciality entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Speciality $speciality)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('speciality_delete', array('id' => $speciality->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Delete Speciality by id
     *
     * @Route("/delete/{id}", name="speciality_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Speciality $speciality)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($speciality);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Speciality was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Speciality');
        }

        return $this->redirect($this->generateUrl('speciality'));

    }


    /**
     * Bulk Action
     * @Route("/bulk-action/", name="speciality_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Speciality');

                foreach ($ids as $id) {
                    $speciality = $repository->find($id);
                    $em->remove($speciality);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'specialities was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the specialities ');
            }
        }

        return $this->redirect($this->generateUrl('speciality'));
    }


}
