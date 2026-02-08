<?php

namespace App\Form;

use App\Entity\OffreAmicale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class OffreAmicaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'الاسم', 'attr' => ['class' => 'form-control']])
            ->add('description', TextareaType::class, ['label' => 'الوصف', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'تاريخ البدء',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'تاريخ الانتهاء',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('dureeEnMois', IntegerType::class, [
                'label' => 'المدة (بالأشهر)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('montantParMois', MoneyType::class, [
                'label' => 'المبلغ الشهري',
                'currency' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('fraisInscription', MoneyType::class, [
                'label' => 'مصاريف التسجيل (اختياري)',
                'currency' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('annee', IntegerType::class, [
                'label' => 'Année',
                'required' => false,
                'attr' => ['class' => 'form-control','value' => date('Y')],
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'نشط' => 'active',
                    'غير نشط' => 'inactive',
                    'منتهي' => 'termine'
                ],
                'label' => 'الحالة',
                'attr' => ['class' => 'form-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffreAmicale::class,
        ]);
    }
}
