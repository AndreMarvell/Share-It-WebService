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
     * @ORM\OneToOne(targetEntity="Covoiturage\TransactionBundle\Entity\Annonce", cascade={"persist"})
     * 
     */
    private $annonce;
    
    /**
     * @ORM\ManyToMany(targetEntity="Covoiturage\UserBundle\Entity\Voyageur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $voyageurs;
    
    function __construct() {
        $this->voyageurs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add voyageur
     *
     * @param \Covoiturage\UserBundle\Entity\Voyageur $voyageur
     * @return Voyageur
     */
    public function addVoyageur(\Covoiturage\UserBundle\Entity\Voyageur $voyageur)
    {
        $this->voyageurs[] = $voyageur;

        return $this;
    }

    /**
     * Remove voyageur
     *
     * @param \Covoiturage\UserBundle\Entity\Voyageur $voyageur
     */
    public function removeVoyageur(\Covoiturage\UserBundle\Entity\Voyageur $voyageur)
    {
        $this->voyageurs->removeElement($voyageur);
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
     * Set annonce
     *
     * @param \Covoiturage\TransactionBundle\Entity\Annonce $annonce
     * @return Voyage
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
     * Get voyageurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVoyageurs()
    {
        return $this->voyageurs;
    }
}
