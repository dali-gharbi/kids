<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vaccine
 *
 * @ORM\Table(name="vaccine")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VaccineRepository")
 */
class Vaccine
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date")
     */
    private $date;

    /**
     * Inversed side
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="User")
     * @ORM\JoinColumn(name="User", referencedColumnName="id", onDelete="CASCADE")
     */
    private $User;

    /**
     * Inversed side
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Address", inversedBy="Address")
     * @ORM\JoinColumn(name="Address", referencedColumnName="id", onDelete="CASCADE")
     */
    private $Address;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Vaccine
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Vaccine
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param string $User
     *
     * @return Vaccine
     */
    public function setUser($User)
    {
        $this->User = $User;

        return $this;
    }

    /**
     * Get User
     *
     * @return string
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * Set Address
     *
     * @param string $Address
     *
     * @return Address
     */
    public function setAddress($Address)
    {
        $this->Address = $Address;

        return $this;
    }

    /**
     * Get Address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->Address;
    }
}

