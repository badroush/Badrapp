<?php

namespace App\Form;

use App\Entity\ChapitreAchatsCollectif;
use App\Entity\ChapitreBudget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ChapitreAchatsCollectifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du chapitre',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('chapitreBudget', EntityType::class, [ // 👈 Ajoute cette ligne
            'class' => ChapitreBudget::class,
            'choice_label' => 'nom',
            'label' => 'Chapitre de budget',
            'attr' => ['class' => 'form-control'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChapitreAchatsCollectif::class,
        ]);
    }
}