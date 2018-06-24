<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Address;
use AppBundle\Entity\Establishment;
use AppBundle\Entity\Event;
use AppBundle\Entity\Rating;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadEventsData
    extends AbstractFixture
    implements ORMFixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $event = new Event();
        $event->setName('3id él omm');
        $event->setDescription('test test');
        $event->setTel('24555666');
        $adrs = new Address();
        $adrs->setRue('test');
        $adrs->setLongitude('36.8179854');
        $adrs->setLatitude('10.1946025');
        $adrs->setGovernorate('tunis');
        $event->setDate(new \DateTime('2000-01-01'));
        $etab = new Establishment();
        $etab->setName('test');
        $etab->setDescription('testdesc');
        $etab->setAddress($adrs);
        $etab->setPhoto('http://ghmf.org/etablissement_image/etablissement01.jpg');
        $rating = new Rating();
        $rating->setRate(5);
        $rating->setDescription('yessser 5ayba');
        $etab->setRating($rating);
        $event->setEstablishment($etab);
        //$pediatre->setRating(1);
        $manager->persist($event);


        $manager->flush();
    }

    /**
     * Sets the container.
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the order of this fixture
     * @return integer
     */
    public function getOrder()
    {
        return 6;
    }

}