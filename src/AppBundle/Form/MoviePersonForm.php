<?php

namespace AppBundle\Form;

use AppBundle\Entity\Person;
use AppBundle\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoviePersonForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('movie_name')
            ->add('movie_year', IntegerType::class)
            ->add('movie_description', TextareaType::class, array(
                'required'   => false,
                'empty_data' => '',
            ))
            ->add('role_name', ChoiceType::class, array(
                'placeholder' => 'Choose role',
                'choices' => array(
                    'Actor' => 'actor',
                    'Director' => 'director',
                    'Writer' => 'writer')))
            ->add('person_name', EntityType::class, array(
                'class' => Person::class,
                'placeholder' => 'Choose person',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Form\Model\MoviePersonModel'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_movie_person_form';
    }
}
