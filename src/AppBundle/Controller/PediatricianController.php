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
 * Pediatrician controller.
 *
 * @Route("/api/pediatricians")
 */
class PediatricianController extends Controller
{

    /**
     * Lists all pediatrician entities.
     *
     * @Route("/", name="api_pediatrician_all")
     * @Method("GET")
     */
    public function ListAction()
    {
        $em=$this->getDoctrine()->getManager();
        $events =$em->getRepository('AppBundle:Pediatrician')->findAll();
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
     * @Route("/search", name="api_pediatrician_search")
     * @Method("GET")
     */
    public function searchPediatreAction(Request $request)
    {
        $text = $request->get('text');
        $em=$this->getDoctrine()->getManager();
        if($text != '') {
            $pedaitricians =$em->getRepository('AppBundle:Pediatrician')->containName($text);
        } else {
            $pedaitricians =$em->getRepository('AppBundle:Pediatrician')->findAll();
        }
        $images = [];
        foreach ($pedaitricians as $ped) {
            $images [] = $this->getUserImage();
        }
        $result = array(
            'pedaitricians'=>$pedaitricians,
            'images' =>$images
        );
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(0);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($result);
        return new JsonResponse($formatted);
    }

    /**
     * Lists all pediatrician entities.
     *
     * @Route("/stats", name="api_count_statas")
     * @Method("GET")
     */
    public function statsAction() {
        $en = $this->getDoctrine()->getManager();
        $query = $en->createQuery('SELECT COUNT(a) FROM AppBundle\Entity\User a');
        $userCount =$query->getSingleScalarResult();
        $query = $en->createQuery('SELECT COUNT(a) FROM AppBundle\Entity\Event a');
        $eventCount =$query->getSingleScalarResult();
        $query = $en->createQuery('SELECT COUNT(a) FROM AppBundle\Entity\Pediatrician a');
        $pediatciciansCount =$query->getSingleScalarResult();
        $res = array(
            'userCount' => $userCount,
            'eventCount' => $eventCount,
            'pediatciciansCount' => $pediatciciansCount
        );
        $normalizer = new ObjectNormalizer();

        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($res);
        return new JsonResponse($formatted);
    }







    private function getUserImage () {
        $number = rand ( 1, 99 );
        //'women',
        $sex = ['men'];
        return'http://api.randomuser.me/portraits/'.$sex[array_rand($sex)].'/'.$number.'.jpg';
    }

}