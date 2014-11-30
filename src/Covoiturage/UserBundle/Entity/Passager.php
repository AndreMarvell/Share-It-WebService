<?php

namespace Covoiturage\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Passager
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Passager extends Users
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
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastTrip", type="datetime", nullable=true)
     */
    private $lastTrip;


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
}
