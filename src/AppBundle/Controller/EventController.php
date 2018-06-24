<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Event controller.
 *
 * @Route("/api/events")
 */
class EventController extends Controller
{

    /**
     * Lists all pediatrician entities.
     *
     * @Route("/", name="api_events_all")
     * @Method("GET")
     */
    public function ListAction()
    {
        $em=$this->getDoctrine()->getManager();
        $events =$em->getRepository('AppBundle:Event')->findAll();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(0);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($events);
        return new JsonResponse($formatted);
    }
}