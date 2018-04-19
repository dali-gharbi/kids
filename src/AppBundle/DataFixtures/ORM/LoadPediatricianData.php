<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Pediatrician;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPediatricianData
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
        $pediatre = new Pediatrician();
        $pediatre->setName('Dr Mostfa Hamdi');
        $pediatre->setAddress('(AIR) assists in the diagnosis and management of children with known or suspected immune deficiencies, rheumatologic (related to muscles, tendons, or joints) or autoimmune (immune response of the body against substance normally present in the body) diseases, and allergic diseases (allergies, asthma, skin allergy, and sinusitis). AIR includes the allergy program, the rheumatology program, and inpatient immunology consultation service. The division also coordinates the pediatric asthma program in collaboration with the Division of Pediatric Pulmonology.');
        $pediatre->setPrice(40);

        //$pediatre->setRating(1);
        $manager->persist($pediatre);


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
        return 4;
    }

}