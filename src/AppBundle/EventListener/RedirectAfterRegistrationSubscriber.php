<?php
/**
 * Created by PhpStorm.
 * User: Bassem
 * Date: 22/06/2018
 * Time: 01:44
 */

namespace AppBundle\EventListener;


use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Psr\Container\ContainerInterface as Container;

class RedirectAfterRegistrationSubscriber implements EventSubscriberInterface
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'uploadImageRegisterSuccess',
            //FOSUserEvents::CHANGE_PASSWORD_COMPLETED =>'registrationCompleted'
        );
    }

    public function uploadImageRegisterSuccess(FormEvent $event)
    {
        $doctrine = $this->container->get('doctrine');
        $em = $doctrine->getManager();

        $user = $event->getForm()->getData();
        $file=$user->getImage();
        $fileName= md5(uniqid()).'.jpg';

        $file->move(
            $this->container->getParameter('image_directory'),$fileName
        );
        $user->setImage($fileName);

        $em->persist($user);
        $em->flush();
    }
}
