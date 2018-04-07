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

        $speciality4 = new Speciality();
        $speciality4->setName('Pediatric Gastroenterology and Hepatology');
        $speciality4->setDescription('specializes in the diagnosis and treatment of disorders involving the gastrointestinal tract (GI), including the esophagus, stomach, intestine tract, liver, biliary tree and pancreas, in children and adolescents. Our division performs an extensive range of related diagnostic and therapeutic procedures, provide nutrition consultations, and manage children before and after liver transplants.');
        $manager->persist($speciality4);

        $speciality5 = new Speciality();
        $speciality5->setName('General Pediatrics and Adolescent Medicine');
        $speciality5->setDescription('offers comprehensive primary care to children from birth through age 21. Our medical team also provides comprehensive hospital care to children of all ages admitted to N.C. Children\'s Hospital, including babies in the newborn nursery at N.C. Women\'s Hospital.');
        $manager->persist($speciality5);

        $speciality6 = new Speciality();
        $speciality6->setName('Pediatric Genetics and Metabolism');
        $speciality6->setDescription('offers diagnosis management and genetic counseling for children and adults with disorders that are known or suspected to be inherited (passed down from a family member), resulting from a change in a person\'s genetic code. Such disorders include birth defects, chromosomal abnormalities, unexplained mental retardation and inborn errors of metabolism (the process of how substances in cells or tissues are broken down for energy or are synthesized).');
        $manager->persist($speciality6);

        $speciality7 = new Speciality();
        $speciality7->setName('Pediatric Hematology-Oncology');
        $speciality7->setDescription(' is a member of the UNC Department of Pediatrics, UNC Lineberger Comprehensive Cancer Center, and the Children\'s Oncology Group. Its mission is to treat and cure children and adolescents with cancer and blood diseases who live in North Carolina through participation both in national and local treatment protocols.');
        $manager->persist($speciality7);

        $speciality8 = new Speciality();
        $speciality8->setName('Pediatric Infectious Diseases');
        $speciality8->setDescription('assists in the diagnosis and management of children with a variety of known or suspected infections as well as immune deficiencies. Children are commonly referred for outpatient evaluation of recurrent or prolonged infections, persistent fever, suspected immune deficiency, enlarged lymph nodes, pneumonia, and a wide range of childhood infections. ');
        $manager->persist($speciality8);

        $speciality9 = new Speciality();
        $speciality9->setName('Neonatal-Perinatal Medicine');
        $speciality9->setDescription('provides care for critically ill newborns and premature infants in the Newborn Critical Care Center (NCCC) at N.C. Children’s Hospital. The division\'s Special Infant Care Clinic also provides evaluation of graduates from the NCCC.');
        $manager->persist($speciality9);

        $speciality10 = new Speciality();
        $speciality10->setName('Pediatric Nephrology and Hypertension');
        $speciality10->setDescription('provides diagnosis and management of kidney disorders and treatment of advanced kidney disease in infants, children and adolescents.');
        $manager->persist($speciality10);

        $speciality11 = new Speciality();
        $speciality11->setName('Pediatric Pulmonology');
        $speciality11->setDescription('provides comprehensive outpatient and inpatient clinical services for infants and children with lung problems. Some of the conditions treated in our clinics include chronic cough, chronic wheezing, recurrent pneumonia, airway problems, bronchitis, apnea (where breathing stops temporarily), sleep disorders, and cystic fibrosis.');
        $manager->persist($speciality11);

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