<?php
// src/Form/ConnexionInternetType.php

namespace App\Form;

use App\Entity\ConnexionInternet;
use App\Entity\Etablissement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConnexionInternetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => 'nom',
                'label' => 'المؤسسة',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('type_connexion', ChoiceType::class, [
                'choices' => [
                    'FIBRE' => 'fibre',
                    'ADSL' => 'adsl',
                    '4G' => '4g',
                    '5G' => '5g',
                    'Satellite' => 'satellite',
                    'VDSL' => 'vdsl',
                ],
                'label' => 'نوع الاتصال',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('reference_modem', TextType::class, [
                'required' => false,
                'label' => 'مرجع المودم',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('numero_ligne', TextType::class, [
                'required' => false,
                'label' => 'رقم الخط',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('debit', TextType::class, [
                'required' => false,
                'label' => 'السرعة (مثال: 10 ميغابت)',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date_mise_en_marche', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'تاريخ التفعيل',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('fournisseur', TextType::class, [
                'required' => false,
                'label' => 'المزود',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('etat_connexion', ChoiceType::class, [
                'choices' => [
                    'نشطة' => 'active',
                    'معطلة' => 'panne',
                    'معلقة' => 'suspendue',
                ],
                'label' => 'حالة الاتصال',
                'attr' => ['class' => 'form-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConnexionInternet::class,
        ]);
    }
}