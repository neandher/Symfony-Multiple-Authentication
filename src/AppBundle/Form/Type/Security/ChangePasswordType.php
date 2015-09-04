<?php

namespace AppBundle\Form\Type\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'current_password',
                'password',
                array(
                    'label' => 'security.change_password.fields.current_password',
                    'mapped' => false,
                    'constraints' => new UserPassword()
                )
            )
            ->add(
                'plainPassword',
                'repeated',
                array(
                    'type'            => 'password',
                    'first_options'   => array('label' => 'security.change_password.fields.new_password'),
                    'second_options'  => array('label' => 'security.change_password.fields.new_password_confirmation'),
                    'invalid_message' => 'security.password.mismatch'
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\User',
                'intention'  => 'change_password'
            )
        );
    }

    public function getName()
    {
        return 'change_password';
    }
}
