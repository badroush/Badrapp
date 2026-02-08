<?php
// src/Form/DemandeMaintenanceType.php

namespace App\Form;

use App\Entity\DemandeMaintenance;
use App\Entity\User;
use App\Entity\Etablissement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\ManagerRegistry;

class DemandeMaintenanceType extends AbstractType
{
    public function __construct(
        private Security $security,
        private ManagerRegistry $doctrine
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }

        // Déterminer les établissements
        $etablissements = null;

        if ($user->isSousAdmin() && $user->getEtablissement()) {
            $etablissements = [$user->getEtablissement()];
        }

        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles(), true)
            || in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true) || in_array('ROLE_TECH', $user->getRoles(), true);

        $builder
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => 'nom',
                'label' => 'المؤسسة',
                'choices' => $etablissements,
                'disabled' => !$isAdmin,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('responsableDemande', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nomp',
                'label' => 'المسؤول عن الطلب',
                'choices' => $this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN') || $this->security->isGranted('ROLE_TECH')
                    ? null // Laisser Doctrine charger tous les utilisateurs
                    : [$user], // Seulement l'utilisateur connecté
                'data' => $this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN') || $this->security->isGranted('ROLE_TECH')
                    ? null // Laisser vide pour forcer la sélection
                    : $user, // Sélectionner l'utilisateur connecté
                'disabled' => !($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN')) && !$this->security->isGranted('ROLE_TECH'),
                'attr' => ['class' => 'form-select'],
            ])
            ->add('technicienAssigne', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nomp',
                'label' => 'التقني المكلف',
                'required' => false,
                'attr' => ['class' => 'form-select'],
                'choices' => array_filter(
                    $this->doctrine->getRepository(User::class)->findAll(),
                    fn(User $u) => in_array('ROLE_TECH', $u->getRoles(), true)
                ),
            ])


            ->add('description', TextareaType::class, [
                'label' => 'الوصف',
                'attr' => ['rows' => 4, 'class' => 'form-control'],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Hardware' => 'hardware',
                    'Software' => 'software',
                    'Network' => 'network',
                    'Other' => 'other',
                ],
                'label' => 'النوع',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('priorite', ChoiceType::class, [
                'choices' => [
                    'منخفضة' => 'faible',
                    'متوسطة' => 'moyenne',
                    'مرتفعة' => 'haute',
                    'حرجة' => 'critique',
                ],
                'label' => 'الأولوية',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'مرسلة' => 'envoyee',
                    'قيد المعالجة' => 'en_cours',
                    'محلولة' => 'resolue',
                    'مؤجلة' => 'reportee',
                    'مرفوضة' => 'rejetee',
                ],
                'label' => 'الحالة',
                'disabled' => !$isAdmin,
                'attr' => ['class' => 'form-select'],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeMaintenance::class,
        ]);
    }
}
