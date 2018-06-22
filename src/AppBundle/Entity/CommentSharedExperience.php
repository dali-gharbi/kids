<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\NotifiableInterface;

/**
 * CommentSharedExperience
 *
 * @ORM\Table(name="comment_shared_experience")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentSharedExperienceRepository")
 * @Notifiable(name="comment_shared_experience")
 */
class CommentSharedExperience implements NotifiableInterface
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SharedExperience", inversedBy="comment_shared_experience", cascade="persist")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     */
    private $sharedExperience;

    /**
     * Inversed side
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="User")
     * @ORM\JoinColumn(name="User", referencedColumnName="id", onDelete="CASCADE")
     */

    private $user;

    /**
     *
     * /**
     * @var string
     *
     * @ORM\Column(name="Discription", type="string", length=2024)
     */
    private $discription;

    /**
     * @var int
     *
     * @ORM\Column(name="likes", type="integer")
     */
    private $likes;


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
     * Set discription
     *
     * @param string $discription
     *
     * @return CommentSharedExperience
     */
    public function setDiscription($discription)
    {
        $this->discription = $discription;

        return $this;
    }

    /**
     * Get discription
     *
     * @return string
     */
    public function getDiscription()
    {
        return $this->discription;
    }

    /**
     * Set likes
     *
     * @param integer $likes
     *
     * @return CommentSharedExperience
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes
     *
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set sharedExperience
     *
     * @param sharedExperience $sharedExperience
     *
     * @return sharedExperience
     */
    public function setSharedExperience($sharedExperience)
    {
        $this->sharedExperience = $sharedExperience;

        return $this;
    }

    /**
     * Get sharedExperience
     *
     * @return SharedExperience
     */
    public function getSharedExperience()
    {
        return $this->sharedExperience;
    }


    /**
     * Set user
     *
     * @param string $user
     *
     * @return CommentSharedExperience
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }
}

