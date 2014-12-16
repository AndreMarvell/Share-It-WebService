<?php

namespace Covoiturage\TransactionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DemandeType extends AbstractType
{
   /* /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
   /* public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date') // Date de la demande, donc pas rajouté ou fait automatiquement par le WS
            ->add('message')
            ->add('prix')
            ->add('annonce')
            ->add('passager') // Identifié par son token, donc retiré. Si existe pas créer un ?
            ->add('comments') // Message et comments ?! 
            ->add('arrivee_propose')
        ;
    }*/
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message')
            ->add('prix')
            //->add('annonce')
            ->add('arrivee_propose', 'adresse')
        ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Covoiturage\TransactionBundle\Entity\Demande',
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
