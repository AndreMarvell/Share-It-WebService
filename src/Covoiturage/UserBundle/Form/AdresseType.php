<?php

namespace Covoiturage\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Covoiturage\UserBundle\Form\DataTransformer\AdresseTransformer;
use Covoiturage\UserBundle\Services\GoogleGeocoding;
use Doctrine\ORM\EntityManager;


class AdresseType extends AbstractType
{
    

    private $em;
    private $geocoding;

    function __construct(GoogleGeocoding $geocoding, EntityManager $em) {
        $this->em = $em;
        $this->geocoding = $geocoding;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $adresseTransformer = new AdresseTransformer($this->geocoding,  $this->em);
     
        $builder->addModelTransformer($adresseTransformer);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected adresse does not exist',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adresse';
        
    }
    
    public function getParent()
    {
        return 'text';
    }
}
