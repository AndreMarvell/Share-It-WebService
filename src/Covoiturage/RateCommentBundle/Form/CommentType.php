<?php

namespace Covoiturage\RateCommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Covoiturage\UserBundle\Form\DataTransformer\UsersTransformer;
use Covoiturage\RateCommentBundle\Form\DataTransformer\CommentThreadTransformer;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userTransformer = new UsersTransformer($options['em']);
        $threadTransformer = new RateThreadTransformer($options['em']);
        
        $builder
            ->add('comment')
            ->add(
                    $builder->create('thread','text')
                            ->addModelTransformer($threadTransformer)
                 )
            ->add(
                    $builder->create('commenter','text')
                            ->addModelTransformer($userTransformer)
                 )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Covoiturage\RateCommentBundle\Entity\Comment',
                    'csrf_protection' => false
                ))
                ->setRequired(array('em'))
                ->setAllowedTypes(array(
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
