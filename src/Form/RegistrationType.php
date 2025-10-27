<?php 

namespace App\Form;
use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [new NotBlank([
                        'message' => 'Veuillez saisir un nom',
                    ])]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [new NotBlank([
                        'message' => 'Veuillez saisir un prénom',
                    ])]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse email',
                    ]),
                ],
                'label' => 'Email',
            ])
            ->add('password', RepeatedType::class, [
                'label' => 'Mot de passe',
                'type' => PasswordType::class,
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
                'constraints' => [new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                    ])]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}