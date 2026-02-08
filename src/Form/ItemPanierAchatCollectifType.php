<?php

namespace App\Form;

use App\Entity\DetailAchatsCollectif;
use App\Entity\PanierAchatCollectif;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ItemPanierAchatCollectif;

class ItemPanierAchatCollectifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('panier', EntityType::class, [
                'class' => PanierAchatCollectif::class,
                'choice_label' => 'id',
                'label' => 'Panier',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('detailAchatCollectif', EntityType::class, [
                'class' => DetailAchatsCollectif::class,
                'choice_label' => 'article.nom',
                'label' => 'Article',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemPanierAchatCollectif::class,
        ]);
    }
}