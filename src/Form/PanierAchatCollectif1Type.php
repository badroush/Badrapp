<?php

namespace App\Form;

use App\Entity\ChapitreAchatsCollectif;
use App\Entity\Etablissement;
use App\Entity\PanierAchatCollectif;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanierAchatCollectif1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('anneeAchats')
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => 'id',
            ])
            ->add('chapitreAchatsCollectif', EntityType::class, [
                'class' => ChapitreAchatsCollectif::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PanierAchatCollectif::class,
        ]);
    }
}
