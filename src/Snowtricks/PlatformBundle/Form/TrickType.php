<?php

namespace Snowtricks\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Snowtricks\PlatformBundle\Form\VideoType;

class TrickType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',           TextType::class)
            ->add('description',    TextareaType::class)
            ->add('trickgroup',     EntityType::class, array(
                'class'         => 'SnowtricksPlatformBundle:TrickGroup',
                'choice_label'  => 'name',
                'expanded'      => false))
            ->add('images',         CollectionType::class, array(
                'entry_type'    => FileType::class,
                'entry_options' => ['label' => false],
                'required'      => false,
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'attr'          => array(
                    'class'         => 'my-selector-images col-sm-8'),
                'mapped'        => false))
            ->add('videos',         CollectionType::class, array(
                'entry_type'    => VideoType::class,
                'entry_options' => ['label' => false],
                'required'      => false,
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'attr'          => array(
                    'class'         => 'my-selector-videos col-sm-8')))
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
