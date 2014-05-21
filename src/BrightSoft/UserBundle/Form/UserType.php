<?php

namespace BrightSoft\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('pass')
//            ->add('slug')
            ->add('email')
            ->add('roles')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BrightSoft\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'brightsoft_userbundle_usertype';
    }
}
