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

    /**
     * Lists all pediatrician entities.
     *
     * @Route("/calendar", name="api_events_all_calendar")
     * @Method("POST")
     */
    public function EventsCalendarAction(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $em=$this->getDoctrine()->getManager();
        $events =$em->getRepository('AppBundle:Event')->getByDate(new \DateTime($start),new \DateTime($end));
        $finalList = [];
        foreach ($events as &$event) {
            $finalList [] = Array (
                                'id'=>$event->getId(),
                                'title' => $event->getName() ,
                                'start' => $event->getDate()->format("Y-m-d")."T00:00:00",
                                'end' => $event->getEndDate()->format("Y-m-d")."T22:00:00");
        }
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(0);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->title;
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($finalList);
        return new JsonResponse($formatted);
    }

    /**
     * Lists all pediatrician entities.
     *
     * @Route("/subscribe", name="api_event_subscribe")
     * @Method("GET")
     */
    public function addToEventAction(Request $request)
    {
        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $username = $user->getUsername();
        }
        $id = $request->get('id');
        $em=$this->getDoctrine()->getManager();
        echo $user->getId();
        echo $id;
        die();
    }
}