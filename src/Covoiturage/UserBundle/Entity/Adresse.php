<?php

namespace Covoiturage\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adresse
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Adresse
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
     * @ORM\Column(name="numero", type="string", length=255, nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="rue", type="string", length=255, length=255, nullable=true)
     */
    private $rue;
    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse_complete", type="string", length=255)
     */
    private $adresseComplete;

    /**
     * @var integer
     *
     * @ORM\Column(name="postal", type="integer", nullable=true, length=255, nullable=true)
     */
    private $postal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true, length=255, nullable=true)
     */
    private $ville;
    
    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, length=255, nullable=true)
     */
    private $region;
    
    /**
     * @var string
     *
     * @ORM\Column(name="departement", type="string", length=255, length=255, nullable=true)
     */
    private $departement;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     */
    private $pays = "France";
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255)
     */
    private $longitude;
    
    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255)
     */
    private $latitude;


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
     * Set adresse
     *
     * @param string $adresse
     * @return Adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set postal
     *
     * @param integer $postal
     * @return Adresse
     */
    public function setPostal($postal)
    {
        $this->postal = $postal;

        return $this;
    }

    /**
     * Get postal
     *
     * @return integer 
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Adresse
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set pays
     *
     * @param string $pays
     * @return Adresse
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string 
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set adresseComplete
     *
     * @param string $adresseComplete
     * @return Adresse
     */
    public function setAdresseComplete($adresseComplete)
    {
        $this->adresseComplete = $adresseComplete;

        return $this;
    }

    /**
     * Get adresseComplete
     *
     * @return string 
     */
    public function getAdresseComplete()
    {
        return $this->adresseComplete;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Adresse
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Adresse
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Adresse
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set rue
     *
     * @param string $rue
     * @return Adresse
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string 
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Adresse
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set departement
     *
     * @param string $departement
     * @return Adresse
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return string 
     */
    public function getDepartement()
    {
        return $this->departement;
    }
}
