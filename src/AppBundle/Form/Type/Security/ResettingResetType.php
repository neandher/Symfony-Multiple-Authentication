<?php

namespace AppBundle\Form\Type\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResettingResetType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'plainPassword',
                'repeated',
                array(
                    'type'          => 'password',
                    'first_options' => array('label' => 'security.resetting.reset.fields.new_password'),
                    'second_options' => array('label' => 'security.resetting.reset.fields.confirm_password'),
                    'invalid_message' => 'security.password.mismatch'
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\User',
                'intention'  => 'resetting_reset'
            )
        );
    }

    public function getName()
    {
        return 'resetting_reset';
    }
}
