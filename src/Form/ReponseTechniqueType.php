<?php
// src/Form/ReponseTechniqueType.php

namespace App\Form;

use App\Entity\ReponseTechnique;
use App\Entity\DemandeMaintenance;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseTechniqueType extends AbstractType
{

public function __construct(
        private \Doctrine\Persistence\ManagerRegistry $doctrine
    ) {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('demande', EntityType::class, [
                'class' => DemandeMaintenance::class,
                'choice_label' => 'id',
                'label' => 'الطلب',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('technicien', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nomp',
                'label' => 'الفني',
                'attr' => ['class' => 'form-select'],
                'choices' => array_filter(
                    $this->doctrine->getRepository(User::class)->findAll(),
                    fn(User $u) => in_array('ROLE_TECH', $u->getRoles(), true)
                ),
            ])
            ->add('contenu', TextareaType::class, [
            'label' => 'محتوى الرد',
            'attr' => ['rows' => 4, 'class' => 'form-control'],
        ])
            ->add('contenu', TextareaType::class, [
                'label' => 'المحتوى',
                'attr' => ['rows' => 4, 'class' => 'form-control'],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponseTechnique::class,
        ]);
    }
}