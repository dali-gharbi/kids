<?php

namespace FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Event controller.
 *
 * @Route("/pediatrician")
 */
class PediatricianController extends Controller
{

    /**
     * Lists all pediatrician entities.
     *
     * @Route("/", name="front_pediatre")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em=$this->getDoctrine()->getManager();
        $pedaitricians =$em->getRepository('AppBundle:Pediatrician')->findAll();
        return $this->render('@Front/Pediatrician/index.html.twig', array(
            'pedaitricians'=>$pedaitricians
        ));
    }
}
