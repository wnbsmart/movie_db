<?php

namespace AppBundle\Form;

use AppBundle\Entity\Person;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class, array(
            'placeholder' => 'Choose role',
            'label' => 'Role',
            'required' => true,
            'choices' => array(
                'Actor' => 'actor',
                'Director' => 'director',
                'Writer' => 'writer')))
            ->add('person', EntityType::class, array(
                'class' => Person::class,
                'placeholder' => 'Choose person',
                'required' => true,
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
            'data_class' => 'AppBundle\Entity\Role'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_role_form';
    }
}
