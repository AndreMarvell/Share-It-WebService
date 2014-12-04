<?php

namespace Covoiturage\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voyage
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Voyage
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
     */
    private $prixFinal;
    
    /**
     * @ORM\OneToOne(targetEntity="Covoiturage\TransactionBundle\Entity\Demande", cascade={"persist"})
     */
    private $demande;


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
}
