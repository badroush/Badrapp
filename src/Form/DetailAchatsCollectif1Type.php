<?php

namespace App\Form;

use App\Entity\ChapitreAchatsCollectif;
use App\Entity\DetailAchatsCollectif;
use App\Entity\Materiel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailAchatsCollectif1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prixVente')
            ->add('chapitreAchatsCollectif', EntityType::class, [
                'class' => ChapitreAchatsCollectif::class,
                'choice_label' => 'id',
            ])
            ->add('article', EntityType::class, [
                'class' => Materiel::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailAchatsCollectif::class,
        ]);
    }
}
