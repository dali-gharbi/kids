<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AncaRebeca\FullCalendarBundle\Model\FullCalendarEvent;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event extends FullCalendarEvent
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
     * @var string
     *
     * @ORM\Column(name="Tel", type="string", length=255)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date")
     */
    private $date;

    /**
     * Inversed side
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Establishment", inversedBy="events", cascade="persist")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     */
    private $Establishment;

    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="Event")
     */
    private $Users;


    public function __construct() {
        $this->Users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function AddUser(User $User)
    {
        $this->Users[] = $User;
    }

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
     * @return Event
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
     * Set name
     *
     * @param string $tel
     *
     * @return Event
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Event
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
     * Set etablishment
     *
     * @param string $etablishment
     *
     * @return Event
     */
    public function setEstablishment($etablishment)
    {
        $this->Establishment = $etablishment;

        return $this;
    }

    /**
     * Get etablishment
     *
     * @return string
     */
    public function getEstablishment()
    {
        return $this->Establishment;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}

