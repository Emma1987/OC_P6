<?php

namespace Snowtricks\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',       TextType::class, array(
                'error_bubbling'  => true))
            ->add('email',          EmailType::class, array(
                'error_bubbling'  => true))
            ->add('plainPassword',  RepeatedType::class, array(
                'type'            => PasswordType::class,
                'error_bubbling'  => true,
                'invalid_message' => 'Les deux mots de passes doivent être identiques',
                'first_options'   => array('label' => 'Mot de passe'),
                'second_options'  => array('label' => 'Confirmer le mot de passe'),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Snowtricks\PlatformBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'snowtricks_platformbundle_user';
    }
}
