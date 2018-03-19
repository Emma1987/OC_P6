<?php

namespace Snowtricks\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TrickType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',           TextType::class)
            ->add('description',    TextType::class)
            ->add('trickgroup',     EntityType::class, array(
                'class'         => 'SnowtricksPlatformBundle:TrickGroup',
                'choice_label'  => 'name',
                'expanded'      => true))
            ->add('images',         CollectionType::class, array(
                'entry_type'    => FileType::class,
                'entry_options' => [
                    'label' => 'Choisissez une nouvelle image',],
                'required'      => false,
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'attr'          => array(
                    'class'         => 'my-selector'),
                'mapped'        => false))
            ->add('save',           SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Snowtricks\PlatformBundle\Entity\Trick'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'snowtricks_platformbundle_trick';
    }


}
