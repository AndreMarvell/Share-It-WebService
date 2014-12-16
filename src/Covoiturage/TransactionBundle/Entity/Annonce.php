<?php

namespace Covoiturage\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annonce
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Annonce
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
     * @ORM\Column(name="date_depart", type="datetime")
     */
    private $dateDepart;

    /**
     * @var boolean
     *
     * @ORM\Column(name="recurrent", type="boolean")
     */
    private $recurrent = false;
    
    /**
     * @ORM\OneToOne(targetEntity="Covoiturage\UserBundle\Entity\Adresse", cascade={"persist"})
     * @ORM\JoinColumn(name="depart_id", referencedColumnName="id", nullable=true)
     */
    private $depart;
    
    /**
     * @ORM\OneToOne(targetEntity="Covoiturage\UserBundle\Entity\Adresse", cascade={"persist"})
     * @ORM\JoinColumn(name="arrivee_id", referencedColumnName="id", nullable=true)
     */
    private $arrivee;
    
    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;
    
    /**
     * @ORM\ManyToOne(targetEntity="Covoiturage\UserBundle\Entity\Conducteur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $conducteur;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="voiture", type="integer")
     */
    private $voiture;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="archivee", type="boolean")
     */
    private $archivee = false;


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
     * Set dateDepart
     *
     * @param \DateTime $dateDepart
     * @return Annonce
     */
    public function setDateDepart($dateDepart)
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    /**
     * Get dateDepart
     *
     * @return \DateTime 
     */
    public function getDateDepart()
    {
        return $this->dateDepart;
    }

    /**
     * Set recurrent
     *
     * @param boolean $recurrent
     * @return Annonce
     */
    public function setRecurrent($recurrent)
    {
        $this->recurrent = $recurrent;

        return $this;
    }

    /**
     * Get recurrent
     *
     * @return boolean 
     */
    public function getRecurrent()
    {
        return $this->recurrent;
    }

    /**
     * Set depart
     *
     * @param \Covoiturage\UserBundle\Entity\Adresse $depart
     * @return Annonce
     */
    public function setDepart(\Covoiturage\UserBundle\Entity\Adresse $depart = null)
    {
        $this->depart = $depart;

        return $this;
    }

    /**
     * Get depart
     *
     * @return \Covoiturage\UserBundle\Entity\Adresse 
     */
    public function getDepart()
    {
        return $this->depart;
    }

    /**
     * Set arrivee
     *
     * @param \Covoiturage\UserBundle\Entity\Adresse $arrivee
     * @return Annonce
     */
    public function setArrivee(\Covoiturage\UserBundle\Entity\Adresse $arrivee = null)
    {
        $this->arrivee = $arrivee;

        return $this;
    }

    /**
     * Get arrivee
     *
     * @return \Covoiturage\UserBundle\Entity\Adresse 
     */
    public function getArrivee()
    {
        return $this->arrivee;
    }

    /**
     * Set prix
     *
     * @param float $prix
     * @return Annonce
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
     * Set conducteur
     *
     * @param \Covoiturage\UserBundle\Entity\Conducteur $conducteur
     * @return Annonce
     */
    public function setConducteur(\Covoiturage\UserBundle\Entity\Conducteur $conducteur)
    {
        $this->conducteur = $conducteur;

        return $this;
    }

    /**
     * Get conducteur
     *
     * @return \Covoiturage\UserBundle\Entity\Conducteur 
     */
    public function getConducteur()
    {
        return $this->conducteur;
    }

    /**
     * Set voiture
     *
     * @param integer $voiture
     * @return Annonce
     */
    public function setVoiture($voiture)
    {
        $this->voiture = $voiture;

        return $this;
    }

    /**
     * Get voiture
     *
     * @return integer 
     */
    public function getVoiture()
    {
        return $this->voiture;
    }

    /**
     * Set archivee
     *
     * @param boolean $archivee
     * @return Annonce
     */
    public function setArchivee($archivee)
    {
        $this->archivee = $archivee;

        return $this;
    }

    /**
     * Get archivee
     *
     * @return boolean 
     */
    public function getArchivee()
    {
        return $this->archivee;
    }
}
