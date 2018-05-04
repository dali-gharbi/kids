<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 *
 * @ORM\Table(name="baby_sitter")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BabySitterRepository")
 */
class BabySitter
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
     * @ORM\Column(name="BirthDay", type="date")
     */
    private $birthDay;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255)
     */
    private $description;

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
    private $Rating;

    /**
     * @var string
     *
     * @ORM\Column(name="Experience", type="string", length=255)
     */
    private $experience;


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
     * @return BabySitter
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
     * Set birthDay
     *
     * @param \DateTime $birthDay
     *
     * @return BabySitter
     */
    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    /**
     * Get birthDay
     *
     * @return \DateTime
     */
    public function getBirthDay()
    {
        return $this->birthDay;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return BabySitter
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return BabySitter
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
     * Set Rating
     *
     * @param string $Rating
     *
     * @return BabySitter
     */
    public function setRating($Rating)
    {
        $this->Rating = $Rating;

        return $this;
    }

    /**
     * Get Rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->Rating;
    }

    /**
     * Set experience
     *
     * @param string $experience
     *
     * @return BabySitter
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return string
     */
    public function getExperience()
    {
        return $this->experience;
    }
}

