<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pediatrician
 *
 * @ORM\Table(name="pediatrician")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PediatricianRepository")
 */
class Pediatrician
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
     * Inversed side
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Address", inversedBy="Address")
     * @ORM\JoinColumn(name="Address", referencedColumnName="id", onDelete="CASCADE")
     */
    private $Address;

    /**
     * @var string
     *
     * @ORM\Column(name="Photo", type="string", length=255)
     */
    private $photo;

    /**
     * Inversed side
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rating", inversedBy="Rating")
     * @ORM\JoinColumn(name="Rating", referencedColumnName="id", onDelete="CASCADE")
     */
    private $rating;


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
     * @return Pediatrician
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
     * Set address
     *
     * @param string $Address
     *
     * @return Pediatrician
     */
    public function setAddress($Address)
    {
        $this->address = $Address;

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

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Pediatrician
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set rating
     *
     * @param string $rating
     *
     * @return Pediatrician
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }
}

