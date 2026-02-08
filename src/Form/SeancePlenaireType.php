<?php

namespace App\Form;

use App\Entity\SeancePlenaire;
use App\Entity\AssociationSportif;
use App\Entity\DocumentSeance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SeancePlenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('association', EntityType::class, [
                'class' => AssociationSportif::class,
                'choice_label' => 'nom',
                'label' => 'الجمعية',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'تاريخ الجلسة',
                'attr' => ['class' => 'form-control', 'value' => date('Y-m-d')],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'نوع الجلسة',
                'choices' => [
                    'جلسة عامة' => 'جلسة عامة',
                    'جلسة إنتخابية' => 'جلسة إنتخابية',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('documents', EntityType::class, [
                'class' => DocumentSeance::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'label' => 'الوثائق',
                'required' => false,
                'attr' => ['class' => 'checkbox-group'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SeancePlenaire::class,
        ]);
    }
}