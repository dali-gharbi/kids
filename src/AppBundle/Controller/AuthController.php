<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pediatrician controller.
 *
 * @Route("/auth")
 */
class AuthController extends Controller
{

    /**
     * Displays a form to create a new Pediatrician entity.
     *
     * @Route("/log", name="logg")
     * @Method("POST")
     */
    public function loginAction(Request $request)
    {
        $json = array();
        $user = new User();

        $username = $request->get('username');
        $password = $request->get('password');
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(
            ['username' => $username ]
        );
        $encoded = $this->get('security.encoder_factory')->getEncoder($user)->isPasswordValid($user->getPassword(),$password, $user->getSalt());
        if ($encoded) {
            array_push($json,[
                'status' => 1,
                'id' => $user->getId()
            ]);
            return new JsonResponse($json);
        } else {
            array_push($json,[
                'status' => 0,
                'id' => 0
            ]);
            return new JsonResponse($json);
        }
    }




}
