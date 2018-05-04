<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use AppBundle\Entity\Pediatrician;

/**
 * Pediatrician controller.
 *
 * @Route("/pediatre")
 */
class PediatricianController extends Controller
{
    /**
     * Lists all Pediatrician entities.
     *
     * @Route("/", name="pediatre")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Pediatrician')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($pediatricians, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('@Admin/pediatrician/index.html.twig', array(
            'pediatricians' => $pediatricians,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,

        ));
    }


    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, $request)
    {
        $filterForm = $this->createForm('AdminBundle\Form\PediatricianFilterType');

        // Bind values from the request
        $filterForm->handleRequest($request);

        if ($filterForm->isValid()) {
            // Build the query from the given form object
            $this->get('petkopara_multi_search.builder')->searchForm( $queryBuilder, $filterForm->get('search'));
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
            return $me->generateUrl('pediatre', $requestParams);
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
     * Displays a form to create a new Pediatrician entity.
     *
     * @Route("/new", name="pediatre_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $pediatrician = new Pediatrician();
        $form   = $this->createForm('AdminBundle\Form\PediatricianType', $pediatrician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pediatrician);
            $em->flush();
            
            $editLink = $this->generateUrl('pediatre_edit', array('id' => $pediatrician->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New pediatrician was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'pediatre' : 'pediatre_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('@Admin/pediatrician/new.html.twig', array(
            'pediatrician' => $pediatrician,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Pediatrician entity.
     *
     * @Route("/{id}", name="pediatre_show")
     * @Method("GET")
     */
    public function showAction(Pediatrician $pediatrician)
    {
        $deleteForm = $this->createDeleteForm($pediatrician);
        return $this->render('@Admin/pediatrician/show.html.twig', array(
            'pediatrician' => $pediatrician,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Pediatrician entity.
     *
     * @Route("/{id}/edit", name="pediatre_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Pediatrician $pediatrician)
    {
        $deleteForm = $this->createDeleteForm($pediatrician);
        $editForm = $this->createForm('AdminBundle\Form\PediatricianType', $pediatrician);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pediatrician);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('pediatre_edit', array('id' => $pediatrician->getId()));
        }
        return $this->render('@Admin/pediatrician/edit.html.twig', array(
            'pediatrician' => $pediatrician,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Pediatrician entity.
     *
     * @Route("/{id}", name="pediatre_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Pediatrician $pediatrician)
    {
    
        $form = $this->createDeleteForm($pediatrician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pediatrician);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Pediatrician was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Pediatrician');
        }
        
        return $this->redirectToRoute('pediatre');
    }
    
    /**
     * Creates a form to delete a Pediatrician entity.
     *
     * @param Pediatrician $pediatrician The Pediatrician entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pediatrician $pediatrician)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pediatre_delete', array('id' => $pediatrician->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Pediatrician by id
     *
     * @Route("/delete/{id}", name="pediatre_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Pediatrician $pediatrician){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($pediatrician);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Pediatrician was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Pediatrician');
        }

        return $this->redirect($this->generateUrl('pediatre'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk-action/", name="pediatre_bulk_action")
    * @Method("POST")
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Pediatrician');

                foreach ($ids as $id) {
                    $pediatrician = $repository->find($id);
                    $em->remove($pediatrician);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'pediatricians was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the pediatricians ');
            }
        }

        return $this->redirect($this->generateUrl('pediatre'));
    }
    

}
