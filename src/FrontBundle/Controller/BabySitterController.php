<?php

namespace FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use AppBundle\Entity\BabySitter;

/**
 * BabySitter controller.
 *
 * @Route("/babysitter")
 */
class BabySitterController extends Controller
{
    /**
     * Lists all BabySitter entities.
     *
     * @Route("/", name="babysitter")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:BabySitter')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($babySitters, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('@Front/babysitter/index.html.twig', array(
            'babySitters' => $babySitters,
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
        $filterForm = $this->createForm('FrontBundle\Form\BabySitterFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('BabySitterControllerFilter');
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
                $session->set('BabySitterControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('BabySitterControllerFilter')) {
                $filterData = $session->get('BabySitterControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('FrontBundle\Form\BabySitterFilterType', $filterData);
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
        $sortCol = $queryBuilder->getRootAlias().'.'.$request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'desc'));
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($request->get('pcg_show' , 10));

        try {
            $pagerfanta->setCurrentPage($request->get('pcg_page', 1));
        } catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }
        
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me, $request)
        {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;
            return $me->generateUrl('babysitter', $requestParams);
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
    protected function getTotalOfRecordsString($queryBuilder, $request) {
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
     * Displays a form to create a new BabySitter entity.
     *
     * @Route("/new", name="babysitter_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $babySitter = new BabySitter();
        $form   = $this->createForm('FrontBundle\Form\BabySitterType', $babySitter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($babySitter);
            $em->flush();
            
            $editLink = $this->generateUrl('babysitter_edit', array('id' => $babySitter->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New babySitter was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'babysitter' : 'babysitter_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('@Front/babysitter/new.html.twig', array(
            'babySitter' => $babySitter,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a BabySitter entity.
     *
     * @Route("/{id}", name="babysitter_show")
     * @Method("GET")
     */
    public function showAction(BabySitter $babySitter)
    {
        $deleteForm = $this->createDeleteForm($babySitter);
        return $this->render('@Front/babysitter/show.html.twig', array(
            'babySitter' => $babySitter,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing BabySitter entity.
     *
     * @Route("/{id}/edit", name="babysitter_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, BabySitter $babySitter)
    {
        $deleteForm = $this->createDeleteForm($babySitter);
        $editForm = $this->createForm('FrontBundle\Form\BabySitterType', $babySitter);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($babySitter);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('babysitter_edit', array('id' => $babySitter->getId()));
        }
        return $this->render('@Front/babysitter/edit.html.twig', array(
            'babySitter' => $babySitter,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a BabySitter entity.
     *
     * @Route("/{id}", name="babysitter_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, BabySitter $babySitter)
    {
    
        $form = $this->createDeleteForm($babySitter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($babySitter);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The BabySitter was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the BabySitter');
        }
        
        return $this->redirectToRoute('babysitter');
    }
    
    /**
     * Creates a form to delete a BabySitter entity.
     *
     * @param BabySitter $babySitter The BabySitter entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(BabySitter $babySitter)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('babysitter_delete', array('id' => $babySitter->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete BabySitter by id
     *
     * @Route("/delete/{id}", name="babysitter_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(BabySitter $babySitter){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($babySitter);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The BabySitter was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the BabySitter');
        }

        return $this->redirect($this->generateUrl('babysitter'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk-action/", name="babysitter_bulk_action")
    * @Method("POST")
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:BabySitter');

                foreach ($ids as $id) {
                    $babySitter = $repository->find($id);
                    $em->remove($babySitter);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'babySitters was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the babySitters ');
            }
        }

        return $this->redirect($this->generateUrl('babysitter'));
    }
    

}
