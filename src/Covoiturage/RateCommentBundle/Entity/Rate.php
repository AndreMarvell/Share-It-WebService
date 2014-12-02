<?php

namespace Covoiturage\RateCommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Rates")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Rate
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this rate
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="Covoiturage\RateCommentBundle\Entity\RateThread")
     */
    protected $thread;
    
    
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Covoiturage\UserBundle\Entity\Users")
     * @var Users
     */
    protected $rater;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="rate", type="integer")
     */
    private $rate;
    
    
    /**
     * Constructor
     */
    function __construct(RateThread $thread, \Covoiturage\UserBundle\Entity\Users $rater) {
        $this->thread = $thread;
        $this->rater = $rater;
    }
    
    
    /**
     * @ORM\prePersist
     */
    public function increase()
    {
        $this->thread->incrementNumRate();
    }

    /**
     * @ORM\preRemove
     */
    public function decrease()
    {
      $this->thread->decrementNumRate();
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
     * Set thread
     *
     * @param \Covoiturage\RateCommentBundle\Entity\RateThread $thread
     * @return Rate
     */
    public function setThread(\Covoiturage\RateCommentBundle\Entity\RateThread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \Covoiturage\RateCommentBundle\Entity\RateThread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set rater
     *
     * @param \Covoiturage\UserBundle\Entity\Users $rater
     * @return Rater
     */
    public function setRater(\Covoiturage\UserBundle\Entity\Users $rater = null)
    {
        $this->rater = $rater;

        return $this;
    }

    /**
     * Get rater
     *
     * @return \Covoiturage\UserBundle\Entity\Users 
     */
    public function getRater()
    {
        return $this->rater;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     * @return Rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer 
     */
    public function getRate()
    {
        return $this->rate;
    }
}
