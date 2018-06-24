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
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Tel", type="string", length=255)
     */
    private $tel;

   // /**
   //  * Inversed side
   //  * @var int
   //  * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Address", inversedBy="Address")
   //  * @ORM\JoinColumn(name="Address", referencedColumnName="id", onDelete="CASCADE")
   //  */

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Address", inversedBy="pediatricians", cascade="persist")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     */
    private $address;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rating", inversedBy="Rating", cascade="persist")
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
     * Set email
     *
     * @param string $email
     *
     * @return Pediatrician
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Pediatrician
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set address
     *
     * @param Address $Address
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
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
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

