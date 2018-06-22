<?php
/**
 * Created by PhpStorm.
 * User: mhdali
 * Date: 26/03/2018
 * Time: 08:21
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 * @Notifiable(name="user")
 */

class User extends BaseUser implements NotifiableInterface
{

    public function __construct()
    {
        parent::__construct();
        $this->Event = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Event", inversedBy="Users")
     * @ORM\JoinTable(name="Attenders")
     */
    private $Event;

    /**
     * @var string
     * @Assert\Image()
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->Event;
    }

    /**
     * @param mixed $Event
     */
    public function setEvent($Event)
    {
        $this->Event = $Event;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }


    public function AddEvent(Event $Event)
    {
        $Event->AddUser($this); // synchronously updating inverse side
        $this->Event[] = $Event;
    }

    public function getId()
    {
        return $this->id;
    }
}