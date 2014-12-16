<?php

namespace Covoiturage\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Demande
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Demande
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;
    
        
    /**
     * @ORM\OneToOne(targetEntity="Covoiturage\TransactionBundle\Entity\Annonce", cascade={"persist"})
     */
    private $annonce;
    
        
    /**
     * @ORM\ManyToOne(targetEntity="Covoiturage\UserBundle\Entity\Passager", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $passager;
    
    /**
     *
     * @ORM\OneToOne(targetEntity="Covoiturage\RateCommentBundle\Entity\CommentThread", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $comments;
    
    /**
     * @ORM\OneToOne(targetEntity="Covoiturage\UserBundle\Entity\Adresse", cascade={"persist"})
     * @ORM\JoinColumn(name="arrivee_id", referencedColumnName="id", nullable=true)
     */
    private $arrivee_propose;
    
    function __construct() {
        $this->date = new \DateTime();
        
    }
    
    /**
     * Creer les entités comment thread
     *
     * @return void 
     */
    public function createThread()
    {
        $this->comments = new \Covoiturage\RateCommentBundle\Entity\CommentThread("demande".$this->id);
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
     * Set date
     *
     * @param \DateTime $date
     * @return Demande
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
     * Set message
     *
     * @param string $message
     * @return Demande
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set prix
     *
     * @param float $prix
     * @return Demande
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set annonce
     *
     * @param \Covoiturage\TransactionBundle\Entity\Annonce $annonce
     * @return Demande
     */
    public function setAnnonce(\Covoiturage\TransactionBundle\Entity\Annonce $annonce = null)
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * Get annonce
     *
     * @return \Covoiturage\TransactionBundle\Entity\Annonce 
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }

    /**
     * Set passager
     *
     * @param \Covoiturage\UserBundle\Entity\Passager $passager
     * @return Demande
     */
    public function setPassager(\Covoiturage\UserBundle\Entity\Passager $passager)
    {
        $this->passager = $passager;

        return $this;
    }

    /**
     * Get passager
     *
     * @return \Covoiturage\UserBundle\Entity\Passager 
     */
    public function getPassager()
    {
        return $this->passager;
    }

    /**
     * Set comments
     *
     * @param \Covoiturage\RateCommentBundle\Entity\CommentThread $comments
     * @return Demande
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

    /**
     * Set arrivee_propose
     *
     * @param \Covoiturage\UserBundle\Entity\Adresse $arriveePropose
     * @return Demande
     */
    public function setArriveePropose(\Covoiturage\UserBundle\Entity\Adresse $arriveePropose = null)
    {
        $this->arrivee_propose = $arriveePropose;

        return $this;
    }

    /**
     * Get arrivee_propose
     *
     * @return \Covoiturage\UserBundle\Entity\Adresse 
     */
    public function getArriveePropose()
    {
        return $this->arrivee_propose;
    }
}
