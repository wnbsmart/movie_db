<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('year')
            ->add('description', TextareaType::class, array(
                'required'   => false,
                'empty_data' => '',
            ))
            ->add('imagePath', FileType::class, array(
                'label' => ' '));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => 'AppBundle\Entity\Movie'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_movie_form_type';
    }
}
