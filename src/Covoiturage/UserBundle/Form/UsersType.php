<?php

namespace Covoiturage\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Covoiturage\UserBundle\Form\DataTransformer\AdresseTransformer;

class UsersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $adresseTransformer = new AdresseTransformer($options['geocoding'],$options['em']);
        
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('username')
            ->add('photo')
            ->add('birthday')
            ->add(
                    $builder->create('adresse','text')
                            ->addModelTransformer($adresseTransformer)
                 )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                    'data_class' => 'Covoiturage\UserBundle\Entity\Users',
                    'csrf_protection' => false
                ))
                ->setRequired(array('geocoding','em'))
                ->setAllowedTypes(array(
                    'geocoding' => 'Covoiturage\UserBundle\Services\GoogleGeocoding',
                    'em' => 'Doctrine\Common\Persistence\ObjectManager'
                ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}