<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Speciality;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadSpecialityData
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
        $speciality = new Speciality();
        $speciality->setName('Pediatric Allergy, Immunology, and Rheumatology');
        $speciality->setDescription('(AIR) assists in the diagnosis and management of children with known or suspected immune deficiencies, rheumatologic (related to muscles, tendons, or joints) or autoimmune (immune response of the body against substance normally present in the body) diseases, and allergic diseases (allergies, asthma, skin allergy, and sinusitis). AIR includes the allergy program, the rheumatology program, and inpatient immunology consultation service. The division also coordinates the pediatric asthma program in collaboration with the Division of Pediatric Pulmonology.');
        $manager->persist($speciality);

        $speciality1 = new Speciality();
        $speciality1->setName('Pediatric Cardiology');
        $speciality1->setDescription('offers modern, comprehensive diagnostic and therapeutic services to patients with known or suspected heart disease. We treat patients ranging in age from the unborn fetus to the young adult, and deliver care both in Chapel Hill and in convenient clinics located throughout North Carolina.');
        $manager->persist($speciality1);

        $speciality2 = new Speciality();
        $speciality2->setName('Pediatric Emergency Medicine');
        $speciality2->setDescription('provides the highest quality and technologically advanced emergency medical care to ill and injured children in the Triangle area, as well as throughout the state. UNC Children\'s emergency department offers a full range of primary, tertiary, and quaternary subspecialty care attached to the Triangle\'s only Level I pediatric trauma center (one of only 41 in the entire U.S.).');
        $manager->persist($speciality2);

        $speciality3 = new Speciality();
        $speciality3->setName('Pediatric Endocrinology and Diabetes');
        $speciality3->setDescription('studies and treats diseases and disorders of the glands in children. Endocrine diseases include growth problems; deficiencies or excesses of hormones from the pituitary, thyroid, adrenal or pancreas; disorders of sex differentiation; and diabetes.');
        $manager->persist($speciality3);

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
        return 3;
    }

}