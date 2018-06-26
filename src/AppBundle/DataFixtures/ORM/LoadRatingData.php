<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Rating;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadRatingData
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
        $rating = new Rating();
        $rating->setRate(0);
        $rating->setDescription('Very bad');
        $manager->persist($rating);

        $rating1 = new Rating();
        $rating1->setRate(1);
        $rating1->setDescription('Bad');
        $manager->persist($rating1);

        $rating2 = new Rating();
        $rating2->setRate(2);
        $rating2->setDescription('Average');
        $manager->persist($rating2);

        $rating3 = new Rating();
        $rating3->setRate(3);
        $rating3->setDescription('Good');
        $manager->persist($rating3);

        $rating4 = new Rating();
        $rating4->setRate(4);
        $rating4->setDescription('Very good');
        $manager->persist($rating4);

        $rating5 = new Rating();
        $rating5->setRate(5);
        $rating5->setDescription('Excellent');
        $manager->persist($rating5);


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
        return 5;
    }

}