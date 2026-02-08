<?php

namespace App\Form;

use App\Entity\RessourceAffectation;
use App\Entity\RessourceBudget;
use App\Entity\Etablissement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RessourceAffectationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idRessource', EntityType::class, [
                'class' => RessourceBudget::class,
                'choice_label' => 'nom',
                'label' => 'الفصل',
                'attr' => ['class' => 'form-select'], 
            ])
            ->add('idEtablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => 'nom',
                'label' => 'المؤسسة',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('montant', NumberType::class, [
                'label' => 'المبلغ',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('annee', IntegerType::class, [
                'label' => 'السنة',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RessourceAffectation::class,
        ]);
    }
}