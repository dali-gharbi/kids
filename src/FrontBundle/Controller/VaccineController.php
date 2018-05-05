<?php

namespace FrontBundle\Controller;

use Symfony\Component\Serializer\Serializer;
use AppBundle\AppBundle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;



use AppBundle\Entity\Vaccine;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Vaccine controller.
 *
 * @Route("/vaccine")
 */
class VaccineController extends Controller
{
    /**
     * Lists all Vaccine entities.
     *
     * @Route("/", name="vaccine")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Vaccine')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($vaccines, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('@Front/vaccine/index.html.twig', array(
            'vaccines' => $vaccines,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,

        ));
    }



    /**
     * Lists all Vaccine entities.
     *
     * @Route("/", name="all")
     * @Method("GET")
     */
    public function allAction()
    {
            $task = $this->getDoctrine()->getManager()->getRepository('AppBundle:Vaccine')->findAll();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);
    }






    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, $request)
    {
        $filterForm = $this->createForm('FrontBundle\Form\VaccineFilterType');

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
            return $me->generateUrl('vaccine', $requestParams);
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
     * Displays a form to create a new Vaccine entity.
     *
     * @Route("/new", name="vaccinee_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $vaccine = new Vaccine();
        $form   = $this->createForm('FrontBundle\Form\VaccineType', $vaccine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vaccine);
            $em->flush();
            
            $editLink = $this->generateUrl('vaccine_edit', array('id' => $vaccine->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New vaccine was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'vaccine' : 'vaccine_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('@Front/vaccine/new.html.twig', array(
            'vaccine' => $vaccine,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Vaccine entity.
     *
     * @Route("/{id}", name="vaccinee_show")
     * @Method("GET")
     */
    public function showAction(Vaccine $vaccine)
    {
        $deleteForm = $this->createDeleteForm($vaccine);
        return $this->render('@Front/vaccine/show.html.twig', array(
            'vaccine' => $vaccine,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Vaccine entity.
     *
     * @Route("/{id}/edit", name="vaccinee_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vaccine $vaccine)
    {
        $deleteForm = $this->createDeleteForm($vaccine);
        $editForm = $this->createForm('FrontBundle\Form\VaccineType', $vaccine);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vaccine);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('vaccine_edit', array('id' => $vaccine->getId()));
        }
        return $this->render('@Front/vaccine/edit.html.twig', array(
            'vaccine' => $vaccine,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Vaccine entity.
     *
     * @Route("/{id}", name="vaccinee_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Vaccine $vaccine)
    {
    
        $form = $this->createDeleteForm($vaccine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vaccine);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Vaccine was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Vaccine');
        }
        
        return $this->redirectToRoute('vaccine');
    }
    
    /**
     * Creates a form to delete a Vaccine entity.
     *
     * @param Vaccine $vaccine The Vaccine entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vaccine $vaccine)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vaccine_delete', array('id' => $vaccine->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Vaccine by id
     *
     * @Route("/delete/{id}", name="vaccinee_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Vaccine $vaccine){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($vaccine);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Vaccine was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Vaccine');
        }

        return $this->redirect($this->generateUrl('vaccine'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk-action/", name="vaccine_bulk_action")
    * @Method("POST")
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Vaccine');

                foreach ($ids as $id) {
                    $vaccine = $repository->find($id);
                    $em->remove($vaccine);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'vaccines was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the vaccines ');
            }
        }

        return $this->redirect($this->generateUrl('vaccine'));
    }



    /**
     * Bulk Action
     * @Route("/recherche/", name="recherche_action")
     * @Method("POST")
     */
    function rechercheListAction(Request $req)

    {
        $em=$this->getDoctrine()->getManager();
        if($req-> isMethod('GET') && $req->get('search') && $req->get('libelle') != '')
        {
            $vaccine = $em->getRepository(Vaccine::class)->findVaccine($req->get('libelle'));

        } else {
            $vaccine =  $em->getRepository(Vaccine::class)->findAll();
        }

        return $this->render('@Front/vaccine/rech.html.twing',array('list'=>$vaccine));
    }



}
