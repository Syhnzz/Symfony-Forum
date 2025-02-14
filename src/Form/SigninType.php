<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class SigninType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Nom Utilisateur',
                'attr' => [
                    'placeholder' => 'Nom Utilisateur'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9_]{3,15}$/',
                        'message' => 'Un utilisateur doit avoir entre 3 et 15 caractère et doit contenir uniquement des lettres, nombre, et underscores.',
                    ])
                ],
            ])
            ->add ('last_name', TextType::class, [
                'label' => 'Nom (Optionnelle)',
                'attr' => [
                    'placeholder' => 'Nom',
                ],
                'required' => false,
            ])
            ->add ('first_name', TextType::class, [
                'label' => 'Prenom (Optionnelle)',
                'attr' => [
                    'placeholder' => 'Prenom',
                ],
                'required' => false,
            ])
            ->add ('age', NumberType::class, [
                'label' => 'Age (Optionnelle)',
                'attr' => [
                    'placeholder' => 'Age',
                ],
                'required' => false,
            ])
            ->add ('tel', NumberType::class, [
                'label' => 'Telephone (Optionnelle)',
                'attr' => [
                    'placeholder' => 'Telephone',
                ],
                'required' => false,
            ])
            ->add ('ville', TextType::class, [
                'label' => 'Ville (Optionnelle)',
                'attr' => [
                    'placeholder' => 'Ville',
                ],
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Mot de passe'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => 'Confirmation du mot de passe',
                'attr' => [
                    'placeholder' => 'Comfirmation du mot de passe'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,}$/',
                        'message' => 'Le mot de passe doit avoir au moins 5 caractères et contenir des lettres et des chiffres.'
                    ]),
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
