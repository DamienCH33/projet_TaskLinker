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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Ex : Sarah',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un prénom.']),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\-\' ]{2,50}$/u',
                        'message' => 'Le prénom ne peut contenir que des lettres, espaces ou tirets.',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Ex : Dupont',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un nom.']),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\-\' ]{2,50}$/u',
                        'message' => 'Le nom ne peut contenir que des lettres, espaces ou tirets.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'attr' => [
                    'placeholder' => 'exemple@entreprise.com',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une adresse e-mail.']),
                    new Email(['message' => 'L’adresse e-mail "{{ value }}" n’est pas valide.']),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Collaborateur' => 'ROLE_USER',
                    'Chef de projet' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'select2',
                    'data-placeholder' => 'Sélectionnez un rôle',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir au moins un rôle.']),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Freelance' => 'Freelance',
                ],
                'placeholder' => 'Sélectionnez un statut',
                'constraints' => [
                    new NotBlank(['message' => 'Le statut est obligatoire.']),
                ],
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date d'entrée",
                'html5' => true,
                'attr' => [
                    'min' => (new \DateTime('-10 years'))->format('Y-m-d'),
                    'max' => (new \DateTime('+1 year'))->format('Y-m-d'),
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La date d\'entrée est obligatoire.']),
                ],
            ]);
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
