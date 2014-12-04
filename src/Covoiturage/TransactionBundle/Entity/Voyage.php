<?php

namespace Covoiturage\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Voyage
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ExclusionPolicy("all") 
 */
class Voyage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code_passager", type="string", length=255)
     */
    private $codePassager;

    /**
     * @var string
     *
     * @ORM\Column(name="code_conducteur", type="string", length=255)
     */
    private $codeConducteur;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_final", type="float")
     * @Expose
     */
    private $prixFinal;
    
    /**
     * @ORM\OneToOne(targetEntity="Covoiturage\TransactionBundle\Entity\Demande", cascade={"persist"})
     * @Expose
     */
    private $demande;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="conducteur_valide", type="boolean")
     * @Expose
     */
    private $conducteurValide = false;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="passager_valide", type="boolean")
     * @Expose
     */
    private $passagerValide = false;


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
     * Set codePassager
     *
     * @param string $codePassager
     * @return Voyage
     */
    public function setCodePassager($codePassager)
    {
        $this->codePassager = $codePassager;

        return $this;
    }

    /**
     * Get codePassager
     *
     * @return string 
     */
    public function getCodePassager()
    {
        return $this->codePassager;
    }

    /**
     * Set codeConducteur
     *
     * @param string $codeConducteur
     * @return Voyage
     */
    public function setCodeConducteur($codeConducteur)
    {
        $this->codeConducteur = $codeConducteur;

        return $this;
    }

    /**
     * Get codeConducteur
     *
     * @return string 
     */
    public function getCodeConducteur()
    {
        return $this->codeConducteur;
    }

    /**
     * Set prixFinal
     *
     * @param float $prixFinal
     * @return Voyage
     */
    public function setPrixFinal($prixFinal)
    {
        $this->prixFinal = $prixFinal;

        return $this;
    }

    /**
     * Get prixFinal
     *
     * @return float 
     */
    public function getPrixFinal()
    {
        return $this->prixFinal;
    }

    /**
     * Set demande
     *
     * @param \Covoiturage\TransactionBundle\Entity\Demande $demande
     * @return Voyage
     */
    public function setDemande(\Covoiturage\TransactionBundle\Entity\Demande $demande = null)
    {
        $this->demande = $demande;

        return $this;
    }

    /**
     * Get demande
     *
     * @return \Covoiturage\TransactionBundle\Entity\Demande 
     */
    public function getDemande()
    {
        return $this->demande;
    }

    /**
     * Set conducteurValide
     *
     * @param boolean $conducteurValide
     * @return Voyage
     */
    public function setConducteurValide($conducteurValide)
    {
        $this->conducteurValide = $conducteurValide;

        return $this;
    }

    /**
     * Get conducteurValide
     *
     * @return boolean 
     */
    public function getConducteurValide()
    {
        return $this->conducteurValide;
    }

    /**
     * Set passagerValide
     *
     * @param boolean $passagerValide
     * @return Voyage
     */
    public function setPassagerValide($passagerValide)
    {
        $this->passagerValide = $passagerValide;

        return $this;
    }

    /**
     * Get passagerValide
     *
     * @return boolean 
     */
    public function getPassagerValide()
    {
        return $this->passagerValide;
    }
}
