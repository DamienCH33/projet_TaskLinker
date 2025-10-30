<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est obligatoire']),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire']),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est obligatoire']),
                    new Email(['message' => 'L\'email n\'est pas valide']),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Collaborateur' => 'ROLE_USER',
                    'Chef de projet' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'label' => 'Rôle',
                'constraints' => [
                    new NotBlank(['message' => 'Vous devez choisir au moins un rôle']),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Freelance' => 'Freelance',
                ],
                'label' => 'Statut',
                'constraints' => [
                    new NotBlank(['message' => 'Le statut est obligatoire']),
                ],
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date d'entrée",
                'constraints' => [
                    new NotBlank(['message' => 'La date d\'entrée est obligatoire']),
                ],
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
