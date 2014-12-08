<?php

namespace Covoiturage\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AdresseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
//            ->add('numero')
//            ->add('rue')
            ->add('adresseComplete')
//            ->add('postal')
//            ->add('ville')
//            ->add('region')
//            ->add('departement')
//            ->add('pays')
//            ->add('longitude')
//            ->add('latitude')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                            'data_class' => 'Covoiturage\UserBundle\Entity\Adresse',
                            'csrf_protection' => false
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
