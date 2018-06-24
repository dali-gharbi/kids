<?php

namespace AdminBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Event;
use AppBundle\Entity\Pediatrician;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\Criteria;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $en = $this->getDoctrine()->getManager();
        $query = $en->createQuery('SELECT COUNT(a) FROM AppBundle\Entity\User a');
        $userCount =$query->getSingleScalarResult();
        $query = $en->createQuery('SELECT COUNT(a) FROM AppBundle\Entity\Event a');
        $eventCount =$query->getSingleScalarResult();
        $query = $en->createQuery('SELECT COUNT(a) FROM AppBundle\Entity\Pediatrician a');
        $pediatciciansCount =$query->getSingleScalarResult();


        // $userCount = $en->getRepository(User::class)->count()->where(Criteria::expr()->gte('floor', 0));
       // $eventCount = $en->getRepository(Event::class)->count()->where(Criteria::expr()->gte('floor', 0));
       // $pediatciciansCount = $en->getRepository(Pediatrician::class)->count()->where(Criteria::expr()->gte('floor', 0));
        return $this->render('@Admin/Default/index.html.twig',array(
            'userCount' => $userCount,
            'eventCount' => $eventCount,
            'pediatciciansCount' => $pediatciciansCount
        ));
    }
}
