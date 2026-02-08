<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\DetailAchatsCollectif;
use Doctrine\ORM\EntityRepository;
use App\Entity\ChapitreAchatsCollectif;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailAchatsCollectifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('article', EntityType::class, [
                'class' => Article::class,
                'choice_label' => 'nom',
                'label' => 'المادة',
                'attr' => ['class' => 'form-control'],
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                  ->orderBy('a.nom', 'ASC'); // 👈 Trie par ordre alphabétique
    },
            ])
            ->add('prixVente', NumberType::class, [
                'label' => 'ثمن الشراء',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('chapitreAchatsCollectif', EntityType::class, [  
            'class' => ChapitreAchatsCollectif::class,
            'choice_label' => 'nom',
            'label' => 'قصل الشراء',
            'attr' => ['class' => 'form-control'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailAchatsCollectif::class,
        ]);
    }
}