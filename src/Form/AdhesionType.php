<?php

namespace App\Form;

use App\Entity\Adhesion;
use App\Entity\Etablissement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class AdhesionType extends AbstractType
{
    public function __construct(
        private Security $security,
    ) {
    }
public function buildForm(FormBuilderInterface $builder, array $options): void
{

$user = $this->security->getUser();

        // Déterminer les établissements disponibles selon le rôle de l'utilisateur
        $etablissements = [];

        if ($user->isSousAdmin() && $user->getEtablissement()) {
            // Sous-admin : ne voir que son établissement
            $etablissements = [$user->getEtablissement()];
        } else {
            // Admin : voir tous les établissements
            $etablissements = null; // Laisser Doctrine charger tous les établissements
        }
    $builder
        ->add('nom', null, [
            'label' => 'الاسم',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('prenom', null , [
            'label' => 'اللقب',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('dateNaissance', DateType::class, [
            'label' => 'تاريخ الميلاد',
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control', 'value' => date('Y-m-d')],
        ])
        ->add('etablissement', null, [
            'label' => 'المؤسسة ',
            'choices' => $etablissements,
            'attr' => ['class' => 'form-control'],
            'disabled' => !in_array('ROLE_ADMIN', $user->getRoles()) && !in_array ('ROLE_SUPER_ADMIN', $user->getRoles()),
        ])
        ->add('adresse', null, [
            'label' => 'العنوان',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('telephone', null, [
            'label' => 'الهاتف',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('etatCivil', ChoiceType::class, [
            'choices' => [
                'أعزب' => 1,
                'متزوج' => 2,
                'مطلق' => 3,
                'أرمل' => 4,
            ],
            'attr' => ['class' => 'form-control'],
        ])
        ->add('anneeAdhesion', null, [
            'label' => 'سنة الانضمام',
            'attr' => ['class' => 'form-control', 'value' => date('Y')],
        ])
        ->add('dateAdhesion', DateType::class, [
            'label' => 'تاريخ الانضمام',
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control', 'value' => date('Y-m-d')],
        ])
        ->add('photo', FileType::class, [
            'label' => 'الصورة (JPEG/PNG)',
            'mapped' => false,
            'required' => false,
            'attr' => ['class' => 'form-control'],
        ]);

    if ($options['is_admin']) {
        $builder->add('etablissement', EntityType::class, [
            'class' => Etablissement::class,
            'choice_label' => 'nom',
            'label' => 'المؤسسة',
            'attr' => ['class' => 'form-control select2-entity'],
        ]);
        // ->add('valider', CheckboxType::class, [
        //         'label' => 'تم التحقق',
        //         'required' => false,
        //     ])
        // ->add('imprimer', DateTimeType::class, [
        //     'label' => 'تاريخ الطباعة',
        //     'widget' => 'single_text',
        //     'required' => false,
        // ]);
    }
}
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Adhesion::class,
        'is_admin' => false,
    ]);
}
}
