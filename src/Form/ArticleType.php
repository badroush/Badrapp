<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Fournisseur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'اسم المنتج',
                'attr' => ['class' => 'form-control'],
                // capital lettre

            ])
            ->add('description', TextareaType::class, [
                'label' => 'الوصف',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 2]
            ])
            ->add('categorie', EntityType::class, [
                'label' => 'التصنيف',
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'placeholder' => 'اختر تصنيفًا',
                'attr' => ['class' => 'form-control select2-entity']
            ])
            ->add('fournisseur', EntityType::class, [
                'label' => 'المورّد',
                'class' => Fournisseur::class,
                'choice_label' => 'nom',
                'placeholder' => 'اختر مورّدًا',
                'attr' => ['class' => 'form-control select2-entity']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
