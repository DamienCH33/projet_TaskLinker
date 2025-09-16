<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AddTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la tâche',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'To Do' => 'To Do',
                    'Doing' => 'Doing',
                    'Done' => 'Done',
                ],
                'label' => 'Statut',
                'required' => true,
            ])
            ->add('employees', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => function (Employee $e) {
                    return $e->getLastname() . ' ' . $e->getFirstname();
                },
                'label' => 'Membre',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
