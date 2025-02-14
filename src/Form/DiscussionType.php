<?php

namespace App\Form;

use App\Entity\Discussion;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscussionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('titre', TextType::class)
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'titre',
                'disabled' => true,
                'placeholder' => 'Selectionner un theme',
                'label' => 'Theme',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Créer une discussion'
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Discussion::class,
        ]);
    }
}
