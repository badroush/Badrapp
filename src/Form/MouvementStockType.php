<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Fournisseur;
use App\Entity\MouvementStock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class MouvementStockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dateMouvement', DateTimeType::class, [
    'label' => 'تاريخ الحركة',
    'widget' => 'single_text',
    'html5' => true,
    'attr' => [
        'class' => 'form-control',
        'min' => (new \DateTime())->format('Y-m-d'), // optionnel : empêche les dates futures
    ],
])
            ->add('type', ChoiceType::class, [
                'label' => 'نوع الحركة',
                'choices' => [
                    '📥 دخول (استلام)' => 'entree',
                    '📤 خروج (بيع/هبة)' => 'sortie',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'الكمية',
                'attr' => ['class' => 'form-control', 'min' => 1],
            ])
            ->add('article', EntityType::class, [
                'label' => 'المنتج',
                'class' => 'App\Entity\Article',
                'choice_label' => 'nom',
                'placeholder' => 'اختر منتجًا',
                'attr' => ['class' => 'form-control select2-entity'], // ← ajouté
            ])
            ->add('fournisseur', EntityType::class, [
                'label' => 'المورّد',
                'class' => 'App\Entity\Fournisseur',
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'اختر مورّدًا',
                'attr' => ['class' => 'form-control select2-entity'],
            ])
            ->add('beneficiaire', EntityType::class, [
                'label' => 'المستفيد',
                'class' => 'App\Entity\Beneficiaire',
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'اختر مستفيدًا',
                'attr' => ['class' => 'form-control select2-entity'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MouvementStock::class,
        ]);
    }
}
