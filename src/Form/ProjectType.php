<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
        'label' => 'Titre du projet',
        'constraints' => [
            new NotBlank([
                'message' => 'Veuillez saisir un titre de projet',
            ])
        ],
        'empty_data' => '', // <- ça convertit null en chaîne vide
    ])

            
            ->add('employees', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => function (Employee $e) {
                    return $e->getLastname() . ' ' . $e->getFirstname();
                },
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'select2'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
