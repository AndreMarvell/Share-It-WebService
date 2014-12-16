<?php

namespace Covoiturage\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Passager
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Passager
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastTrip", type="datetime", nullable=true)
     */
    private $lastTrip;
    
    /**
     * @ORM\OneToOne(targetEntity="Covoiturage\UserBundle\Entity\Users", cascade={"persist"})
     */
    private $user;
    
    /** 
     *
     * @ORM\OneToOne(targetEntity="Covoiturage\RateCommentBundle\Entity\RateThread")
     * @ORM\JoinColumn(nullable=true)
     */
    private $rate;
    
    /**
     *
     * @ORM\OneToOne(targetEntity="Covoiturage\RateCommentBundle\Entity\CommentThread")
     * @ORM\JoinColumn(nullable=true)
     */
    private $comments;
    
    
    function __construct($user) {
        $this->user = $user;
        $this->comments = new \Covoiturage\RateCommentBundle\Entity\CommentThread();
        $this->rate = new \Covoiturage\RateCommentBundle\Entity\RateThread();
    }

        
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Passager
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set lastTrip
     *
     * @param \DateTime $lastTrip
     * @return Passager
     */
    public function setLastTrip($lastTrip)
    {
        $this->lastTrip = $lastTrip;

        return $this;
    }

    /**
     * Get lastTrip
     *
     * @return \DateTime 
     */
    public function getLastTrip()
    {
        return $this->lastTrip;
    }

    /**
     * Set user
     *
     * @param \Covoiturage\UserBundle\Entity\Users $user
     * @return Passager
     */
    public function setUser(\Covoiturage\UserBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Covoiturage\UserBundle\Entity\Users 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set rate
     *
     * @param \Covoiturage\RateCommentBundle\Entity\RateThread $rate
     * @return Passager
     */
    public function setRate(\Covoiturage\RateCommentBundle\Entity\RateThread $rate = null)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return \Covoiturage\RateCommentBundle\Entity\RateThread 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set comments
     *
     * @param \Covoiturage\RateCommentBundle\Entity\CommentThread $comments
     * @return Passager
     */
    public function setComments(\Covoiturage\RateCommentBundle\Entity\CommentThread $comments = null)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return \Covoiturage\RateCommentBundle\Entity\CommentThread 
     */
    public function getComments()
    {
        return $this->comments;
    }
}
