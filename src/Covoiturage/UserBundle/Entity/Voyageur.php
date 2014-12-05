<?php

namespace Covoiturage\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Voyageur
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ExclusionPolicy("all") 
 */
class Voyageur
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
     * @var float
     *
     * @ORM\Column(name="prix_final", type="float")
     * @Expose
     */
    private $prixFinal;
    
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
     * @ORM\ManyToOne(targetEntity="Covoiturage\UserBundle\Entity\Passager", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $passager;


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
     * Set prixFinal
     *
     * @param float $prixFinal
     * @return Voyageur
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
     * Set codePassager
     *
     * @param string $codePassager
     * @return Voyageur
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
     * @return Voyageur
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
     * Set conducteurValide
     *
     * @param boolean $conducteurValide
     * @return Voyageur
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
     * @return Voyageur
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

    /**
     * Set passager
     *
     * @param \Covoiturage\UserBundle\Entity\Passager $passager
     * @return Voyageur
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
}
