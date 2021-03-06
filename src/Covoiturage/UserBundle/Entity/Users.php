<?php

namespace Covoiturage\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Users
 * @ORM\Table()
 * @ORM\Entity
 * @ExclusionPolicy("all") 
 */
class Users
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
     * @var integer
     *
     * @ORM\Column(name="pin", type="integer", nullable=true)
     * 
     */
    private $pin;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     * @Expose
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     * @Expose
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Expose
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="Covoiturage\UserBundle\Entity\Adresse", cascade={"persist"})
     * @ORM\JoinColumn(name="adresse_id", referencedColumnName="id", nullable=true)
     * @Expose
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     * @Expose
     */
    private $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     * @Expose
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * @Expose
     */
    private $username;

    /**
     * @var integer
     *
     * @ORM\Column(name="role", type="integer")
     * @Expose
     */
    private $role = 1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="locked", type="boolean")
     * @Expose
     */
    private $locked = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="datetime")
     * @Expose
     */
    private $dateInscription;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     * @Expose
     */
    private $photo = "unknow.jpg";

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;
    
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lockedAt", type="datetime", nullable=true)
     * @Expose
     */
    private $lockedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="datetime", nullable=true)
     * @Expose
     */
    private $birthday;
    
    /**
     * @var float
     *
     * @ORM\Column(name="credits", type="float")
     * @Expose
     */
    private $credits = 0;
    

    
    
    function __construct() {
        $this->dateInscription = new \DateTime();
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
     * Set nom
     *
     * @param string $nom
     * @return Users
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Users
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     * @return Users
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Users
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set role
     *
     * @param integer $role
     * @return Users
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return integer 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return Users
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean 
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set dateInscription
     *
     * @param \DateTime $dateInscription
     * @return Users
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * Get dateInscription
     *
     * @return \DateTime 
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Users
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set lockedAt
     *
     * @param \DateTime $lockedAt
     * @return Users
     */
    public function setLockedAt($lockedAt)
    {
        $this->lockedAt = $lockedAt;

        return $this;
    }

    /**
     * Get lockedAt
     *
     * @return \DateTime 
     */
    public function getLockedAt()
    {
        return $this->lockedAt;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return Users
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set adresse
     *
     * @param \Covoiturage\UserBundle\Entity\Adresse $adresse
     * @return Users
     */
    public function setAdresse(\Covoiturage\UserBundle\Entity\Adresse $adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return \Covoiturage\UserBundle\Entity\Adresse 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set rate
     *
     * @param \Covoiturage\RateCommentBundle\Entity\RateThread $rate
     * @return Users
     */
    public function setRate(\Covoiturage\RateCommentBundle\Entity\RateThread $rate = null)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return \Covoiturage\RateCommentBundle\Entity\RateThread 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set credits
     *
     * @param string $credits
     * @return Users
     */
    public function setCredits($credits)
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * Get credits
     *
     * @return string 
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Users
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set pin
     *
     * @param integer $pin
     * @return Users
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return integer 
     */
    public function getPin()
    {
        return $this->pin;
    }
}
