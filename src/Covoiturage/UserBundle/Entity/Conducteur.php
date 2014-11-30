<?php

namespace Covoiturage\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conducteur
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Conducteur extends Users
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
}