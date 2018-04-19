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

    public function __construct()
    {
    }
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

    ///**
    // * @var string
    // *
   //  * @ORM\Column(name="Photo", type="string", length=255)
   //  */
   // private $photo;

    /**
     * @var float
     *
     * @ORM\Column(name="Price", type="float")
     */
    private $price;

    /**
     * Inversed side
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rating", inversedBy="Rating")
     * @ORM\JoinColumn(name="Rating", referencedColumnName="id", onDelete="CASCADE")
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Speciality", inversedBy="pediatricians", cascade="persist")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     */
    private $speciality;


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

   // /**
   //  * Set photo
   //  *
   //  * @param string $photo
   //  *
   //  * @return Pediatrician
   //  */
   // public function setPhoto($photo)
   // {
   //     $this->photo = $photo;
//
  //      return $this;
  //  }

  //  /**
  //   * Get photo
  //   *
  //   * @return string
  //   */
  //  public function getPhoto()
  //  {
  //      return $this->photo;
  //  }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Pediatrician
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
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

    /**
     * Get speciality
     *
     * @return Speciality
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set speciality
     *
     * @param Speciality $speciality
     *
     * @return Speciality
     */
    public function setSpeciality(Speciality $speciality)
    {
        $this->speciality = $speciality;
        return $this->speciality;
    }
}

