<?php

namespace Covoiturage\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conducteur
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Conducteur
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
     * @var string
     *
     * @ORM\Column(name="License", type="string", length=255, nullable=true)
     */
    private $license;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="LicenseObtainedAt", type="datetime", nullable=true)
     */
    private $licenseObtainedAt;

    
    /**
     * @ORM\OneToOne(targetEntity="Covoiturage\UserBundle\Entity\Users", cascade={"persist"})
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastTrip", type="datetime", nullable=true)
     */
    private $lastTrip;
    
    /**
     * @ORM\ManyToMany(targetEntity="Covoiturage\UserBundle\Entity\Voiture", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $voitures;
    
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
    
    function __construct() {
        $this->voitures = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set license
     *
     * @param string $license
     * @return Conducteur
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Get license
     *
     * @return string 
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Set licenseObtainedAt
     *
     * @param \DateTime $licenseObtainedAt
     * @return Conducteur
     */
    public function setLicenseObtainedAt($licenseObtainedAt)
    {
        $this->licenseObtainedAt = $licenseObtainedAt;

        return $this;
    }

    /**
     * Get licenseObtainedAt
     *
     * @return \DateTime 
     */
    public function getLicenseObtainedAt()
    {
        return $this->licenseObtainedAt;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Conducteur
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
     * @return Conducteur
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
     * Add voiture
     *
     * @param \Covoiturage\UserBundle\Entity\Voiture $voiture
     * @return Voiture
     */
    public function addVoiture(\Covoiturage\UserBundle\Entity\Voiture $voiture)
    {
        $this->voitures[] = $voiture;

        return $this;
    }

    /**
     * Remove voiture
     *
     * @param \Covoiturage\UserBundle\Entity\Voiture $voiture
     */
    public function removeVoiture(\Covoiturage\UserBundle\Entity\Voiture $voiture)
    {
        $this->voitures->removeElement($voiture);
    }

    /**
     * Get voitures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVoitures()
    {
        return $this->voitures;
    }

    /**
     * Set user
     *
     * @param \Covoiturage\UserBundle\Entity\Users $user
     * @return Conducteur
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
     * @return Conducteur
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
     * @return Conducteur
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
