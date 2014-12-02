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
    private $recurrent;
    
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
}
