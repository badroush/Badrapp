<?php

namespace App\Form;

use App\Entity\ActionControle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionControleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('action', TextType::class, [
                'label' => 'Action',
                'attr' => ['class' => 'form-control']
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Sous-admin' => 'ROLE_SOUS_ADMIN',
                    'Admin' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN',
                    'Directeur Jeunesse' => 'ROLE_DRJ',
                    'Directeur Sport' => 'ROLE_DRS',
                    'Magasinier' => 'ROLE_MAG',
                    'Financier' => 'ROLE_FNC',
                    'Technicien' => 'ROLE_TECH',
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('active', null, [
                'label' => 'Active',
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('masque', null, [
                'label' => 'Masqué',
                'attr' => ['class' => 'form-check-input']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActionControle::class,
        ]);
    }
}