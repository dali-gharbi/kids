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
 * @Route("/auth")
 */
class AuthController extends Controller
{

    /**
     * Displays a form to create a new Pediatrician entity.
     *
     * @Route("/login", name="loginapi")
     * @Method({"POST"})
     */
    public function loginAction(Request $request)
    {
        $parametersAsArray = [];
        $result = array();
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
            $user_manager = $this->get('fos_user.user_manager');
            $factory = $this->get('security.encoder_factory');
            $user = $user_manager->findUserByUsername($parametersAsArray['username']);
            $encoder = $factory->getEncoder($user);
            $bool = ($encoder->isPasswordValid($user->getPassword(),$parametersAsArray['password'],$user->getSalt())) ? "true" : "false";
            if($bool) {
                $result =  array('valid' => $bool, 'username' => $user->getUsername(), 'role' => $user->getRoles());
            } else {
                $result = array('valid' => false);
            }
        } else {
            $result =  array('valid' => false);
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($result);
        return new JsonResponse($formatted);
    }
}
