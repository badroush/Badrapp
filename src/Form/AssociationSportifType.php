<?php

namespace App\Form;

use App\Entity\AssociationSportif;
use App\Entity\Delegation;
use App\Entity\ClasseSportif;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssociationSportifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('delegation', EntityType::class, [
                'class' => Delegation::class,
                'choice_label' => 'nom',
                'label' => 'المعتمدية',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('classeSportif', EntityType::class, [
                'class' => ClasseSportif::class,
                'choice_label' => 'nom',
                'label' => 'الشعبة الرياضية',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nom', TextType::class, [
                'label' => 'اسم الجمعية',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'العنوان',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nombreMales', IntegerType::class, [
                'label' => 'عدد الذكور',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nombreFemelles', IntegerType::class, [
                'label' => 'عدد الإناث',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('specialites', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'التخصصات',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateConstruction', DateType::class, [
                'widget' => 'single_text',
                'label' => 'تاريخ الإنشاء',
                'attr' => ['class' => 'form-control', 'value' => date('Y-m-d')],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'الهاتف',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('fax', TextType::class, [
                'label' => 'الفاكس',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('directeur', TextType::class, [
                'label' => 'المدير',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('telDirecteur', TextType::class, [
                'label' => 'هاتف المدير',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('directeurAdjoint', TextType::class, [
                'label' => 'نائب المدير',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('telDirAdj', TextType::class, [
                'label' => 'هاتف نائب المدير',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('secretariatGeneral', TextType::class, [
                'label' => 'الكاتب العام',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('telSecGen', TextType::class, [
                'label' => 'هاتف الكاتب العام',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('tresorier', TextType::class, [
                'label' => 'أمين المال',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('telTres', TextType::class, [
                'label' => 'هاتف أمين المال',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AssociationSportif::class,
        ]);
    }
}