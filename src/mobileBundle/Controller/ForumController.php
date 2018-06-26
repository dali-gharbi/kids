<?php
/**
 * Created by PhpStorm.
 * User: yossr
 * Date: 25/06/2018
 * Time: 22:37
 */

namespace mobileBundle\Controller;
use AppBundle\Entity\Theme;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class ForumController  extends Controller
{


    public function findallAction()
    {
        $events = $this->getDoctrine()->getManager();
           $raw= 'select * from theme';
        $stat=$events->getConnection()->prepare($raw);
        $stat->execute();
        $tot=$stat->fetchAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tot);
        return new JsonResponse($formatted);
    }

    public function findallExprienceAction(Request $request)
    {
        $id = $request->get('id');
        $events = $this->getDoctrine()->getManager();
        $raw= 'select * from shared_experience WHERE theme_id='.$id;
        $stat=$events->getConnection()->prepare($raw);
        $stat->execute();
        $tot=$stat->fetchAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tot);
        return new JsonResponse($formatted);
    }
    public function findallCommentExprienceAction(Request $request)
    {
        $id = $request->get('id');
        $events = $this->getDoctrine()->getManager();
        $raw= 'select * from comment_shared_experience WHERE shared_experience_id	='.$id;
        $stat=$events->getConnection()->prepare($raw);
        $stat->execute();
        $tot=$stat->fetchAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tot);
        return new JsonResponse($formatted);
    }

}