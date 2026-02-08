<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Etablissement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// file type
use Symfony\Component\Form\Extension\Core\Type\FileType;
//file
use Symfony\Component\Validator\Constraints\File;
use App\Entity\Grade;
use Symfony\Bundle\SecurityBundle\Security;


class ProfileFormType extends AbstractType
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
        $grades = [];

        if ($user->isSousAdmin() && $user->getEtablissement()) {
            // Sous-admin : ne voir que son établissement
            $etablissements = [$user->getEtablissement()];
            $grades = $user->getGrade();
        } else {
            // Admin : voir tous les établissements
            $etablissements = null; // Laisser Doctrine charger tous les établissements
            $grades = null;
        }
        $builder
            ->add('email', EmailType::class, [
                'label' => 'البريد الإلكتروني',
                'disabled' => true, // On ne permet pas de changer l'email
                'disabled' => $user->isSousAdmin(), // Optionnel : désactiver le champ pour les sous-admins
            ])
            ->add('nomp', TextType::class, [
                'label' => 'الاسم',
            ])
            ->add('matricule', TextType::class, [
                'label' => 'المعرف الوحيد',
                'required' => false,
                'disabled' => $user->isSousAdmin(), // Optionnel : désactiver le champ pour les sous-admins
            ])
            ->add('cin', TextType::class, [
                'label' => 'ر.ب.ت.و',
                'required' => false,
                'disabled' => $user->isSousAdmin(), // Optionnel : désactiver le champ pour les sous-admins
            ])
            ->add('grade', EntityType::class, [
                'class' => Grade::class,
                'choices' => $grades, // Restreindre selon le rôle
                'choice_label' => 'nom',
                'label' => 'الرتبة',
                'attr' => ['class' => 'form-select'],
                'disabled' => $user->isSousAdmin(), // Optionnel : désactiver le champ pour les sous-admins
            ])
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choices' => $etablissements, // Restreindre selon le rôle
                'choice_label' => 'nom',
                'label' => 'المؤسسة',
                'attr' => ['class' => 'form-select'],
                'disabled' => $user->isSousAdmin(), // Optionnel : désactiver le champ pour les sous-admins
            ])
            ->add('avatar', FileType::class, [
                'label' => 'الصورة الرمزية',
                'required' => false,
                'mapped' => false, // Important : ce champ n'est pas directement lié à l'entité
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'الرجاء تحميل صورة صالحة (JPEG, PNG, WebP, GIF)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
