<?php

namespace FrontBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    /**
     * Lists all pediatrician entities.
     *
     * @Route("/all", name="front_list")
     * @Method("GET")
     */
    public function List()
    {
        $em=$this->getDoctrine()->getManager();
        $pedaitricians =$em->getRepository('AppBundle:Pediatrician')->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($pedaitricians);
        return new JsonResponse($formatted);
    }
}
