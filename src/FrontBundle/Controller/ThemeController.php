<?php

namespace FrontBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use Symfony\Component\Asset\Package;
use AppBundle\Entity\Theme;

/**
 * Theme controller.
 *
 * @Route("/themes")
 */
class ThemeController extends Controller
{
    /**
     * Lists all Theme entities.
     *
     * @Route("/", name="themes")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Theme')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($themes, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('@Front/theme/index.html.twig', array(
            'themes' => $themes,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,

        ));
    }


    /**
     * @Route("/new", name="theme_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $theme = new Theme();
        $form = $this->createForm('FrontBundle\Form\ThemeType', $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $file
             */

            $file = $theme->getImage();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('image_directory'), $fileName
            );
            $theme->setimage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            $editLink = $this->generateUrl('themes_edit', array('id' => $theme->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New theme was created successfully.</a>");

            $nextAction = $request->get('submit') == 'save' ? 'theme' : 'theme_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('@Front/theme/new.html.twig', array(
            'theme' => $theme,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a Theme entity.
     *
     * @Route("/{id}", name="theme_show")
     * @Method("GET")
     */
    public function showAction(Theme $theme)
    {
        $deleteForm = $this->createDeleteForm($theme);
        return $this->render('@Front/theme/show.html.twig', array(
            'theme' => $theme,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing Theme entity.
     *
     * @Route("/{id}/edit", name="theme_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Theme $theme)
    {
        $deleteForm = $this->createDeleteForm($theme);
        $editForm = $this->createForm('FrontBundle\Form\ThemeType', $theme);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /**
             * @var UploadedFile $file
             */
            $file = $theme->getImage();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();


            $file->move(
                $this->getParameter('image_directory'), $fileName
            );
            $theme->setimage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('theme_edit', array('id' => $theme->getId()));
        }
        return $this->render('@Front/theme/edit.html.twig', array(
            'theme' => $theme,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a Theme entity.
     *
     * @Route("/{id}", name="theme_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Theme $theme)
    {

        $form = $this->createDeleteForm($theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($theme);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Theme was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Theme');
        }

        return $this->redirectToRoute('themes');
    }

    /**
     * Creates a form to delete a Theme entity.
     *
     * @param Theme $theme The Theme entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Theme $theme)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('theme_edit', array('id' => $theme->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Delete Theme by id
     *
     * @Route("/delete/{id}", name="theme_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Theme $theme)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($theme);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Theme was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Theme');
        }

        return $this->redirect($this->generateUrl('theme'));

    }


    /**
     * Bulk Action
     * @Route("/bulk-action/", name="theme_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('AppBundle:Theme');

                foreach ($ids as $id) {
                    $theme = $repository->find($id);
                    $em->remove($theme);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'themes was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the themes ');
            }
        }

        return $this->redirect($this->generateUrl('theme'));
    }

    /**
     * Create filter form and process filter request.
     *
     */
    protected function filter($queryBuilder, Request $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('FrontBundle\Form\ThemeFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ThemeControllerFilter');
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
                $session->set('ThemeControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ThemeControllerFilter')) {
                $filterData = $session->get('ThemeControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('FrontBundle\Form\ThemeFilterType', $filterData);
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
            return $me->generateUrl('themes', $requestParams);
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

}
