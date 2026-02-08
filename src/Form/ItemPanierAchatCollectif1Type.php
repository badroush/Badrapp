<?php

namespace App\Form;

use App\Entity\DetailAchatsCollectif;
use App\Entity\ItemPanierAchatCollectif;
use App\Entity\PanierAchatCollectif;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemPanierAchatCollectif1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('panier', EntityType::class, [
                'class' => PanierAchatCollectif::class,
                'choice_label' => 'id',
            ])
            ->add('detailAchatCollectif', EntityType::class, [
                'class' => DetailAchatsCollectif::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemPanierAchatCollectif::class,
        ]);
    }
}
