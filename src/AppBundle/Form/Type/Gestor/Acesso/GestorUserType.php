<?php

namespace AppBundle\Form\Type\Gestor\Acesso;

use AppBundle\Form\Type\Security\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GestorUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text')
            ->add('lastName', 'text')
            ->add('user', new UserType());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Gestor\Acesso\GestorUser'
            )
        );
    }

    public function getName()
    {
        return 'app_bundle_gestor_user_type';
    }
}
