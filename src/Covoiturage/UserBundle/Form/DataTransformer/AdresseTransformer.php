<?php

namespace Covoiturage\UserBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

use Covoiturage\UserBundle\Services\GoogleGeocoding;
use Covoiturage\UserBundle\Entity\Adresse;
use Doctrine\Common\Persistence\ObjectManager;



class AdresseTransformer implements DataTransformerInterface
{
    /**
     * @var GoogleGeocoding
     */
    private $geocode;
    /**
     *  Entité et Repository
     */
    private $entityClass;
    private $entityType;
    private $entityRepository;
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param GoogleGeocoding $geocode
     */
    public function __construct(GoogleGeocoding $geocode,ObjectManager $om)
    {
        $this->geocode = $geocode;
        $this->om = $om;
        $this->setEntityClass("Covoiturage\UserBundle\Entity\Adresse");
        $this->setEntityRepository("CovoiturageUserBundle:Adresse");
        $this->setEntityType("Adresse");
        
    }

    /**
     * Transforme un objet adresse  en adresse textuelle .
     *
     * @param  Adresse|null $adresse
     * @return string
     */
    public function transform($adresse)
    {
        if (null === $adresse) {
            return "";
        }

        return $adresse->getAdresseComplete();
    }

    /**
     * Transforme une adresse textuelle en objet Adresse.
     *
     * @param  string adresse
     *
     * @return Adresse|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($adresse)
    {
        
        $repository = $this->om->getRepository($this->entityRepository);
        $a = $repository->findOneByAdresseComplete($adresse);
        
        if(is_null($a) || !is_object($a)){
            $a = $this->geocode->geoCodeAddress($adresse);
            if(is_null($a) || !is_object($a)){
                throw new TransformationFailedException(sprintf(
                    'Le récupération de l\'adresse %s a échoué',
                    $adresse
                ));
            }
        }
        return $a;
    }
    
    public function setEntityType($entityType){$this->entityType = $entityType;}
 
    public function setEntityClass($entityClass){$this->entityClass = $entityClass;}
 
    public function setEntityRepository($entityRepository){$this->entityRepository = $entityRepository;}
   

}