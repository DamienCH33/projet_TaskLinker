<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Count;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la tâche',
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire.']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire.']),
                ],
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date limite',
                'constraints' => [
                    new NotBlank(['message' => 'La date limite est obligatoire.']),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'To Do' => 'To Do',
                    'Doing' => 'Doing',
                    'Done' => 'Done',
                ],
                'label' => 'Statut',
                'constraints' => [
                    new NotBlank(['message' => 'Le statut est obligatoire.']),
                ],
            ])
            ->add('employees', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => fn(Employee $e) => $e->getLastname() . ' ' . $e->getFirstname(),
                'label' => 'Membres assignés',
                'multiple' => true,
                'expanded' => false,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Veuillez sélectionner au moins un membre.',
                    ]),
                ],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
