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

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */

class User extends BaseUser
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