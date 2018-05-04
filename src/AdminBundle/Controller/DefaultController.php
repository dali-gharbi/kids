<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $en = $this->getDoctrine()->getManager()->getRepository("AppBundle:User");
        return $this->render('@Admin/Default/index.html.twig');
    }
}
