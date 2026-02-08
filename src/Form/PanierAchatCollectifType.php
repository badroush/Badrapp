<?php

namespace App\Form;

use App\Entity\ChapitreAchatsCollectif;
use App\Entity\Etablissement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\PanierAchatCollectif;

class PanierAchatCollectifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => 'nom',
                'label' => 'Établissement',
            ])
            ->add('chapitreAchatsCollectif', EntityType::class, [
                'class' => ChapitreAchatsCollectif::class,
                'choice_label' => 'nom',
                'label' => 'Chapitre d\'achats',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('anneeAchats', IntegerType::class, [
                'label' => 'Année d\'achats',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PanierAchatCollectif::class,
        ]);
    }
}