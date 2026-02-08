<?php

namespace App\Form;

use App\Entity\Etablissement;
use App\Entity\Materiel;
<<<<<<< HEAD
use App\Entity\LibelleMateriel;
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class MaterielType extends AbstractType
{
    public function __construct(
        private Security $security,
<<<<<<< HEAD
    ) {}
=======
    ) {
    }
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $etablissements = [];

        if ($user->isSousAdmin() && $user->getEtablissement()) {
            // Sous-admin : ne voir que son établissement
            $etablissements = [$user->getEtablissement()];
        } else {
            // Admin : voir tous les établissements
            $etablissements = null; // Laisser Doctrine charger tous les établissements
        }
        $builder
<<<<<<< HEAD
                ->add('libelleMateriel', null, [ // ✅ Remplacer EntityType par null
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'ابحث عن المادة...',
                        'data-field' => 'nom', // ✅ Indiquer le champ concerné
                        'autocomplete' => 'off',
                        'required' => true,
                    ],
                ])
            ->add('emplacement', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'أدخل الموقع',
                    'required' => false,
                ],
            ])
            ->add('serie', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'أدخل الرقم التسلسلي',
=======
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom du matériel',
                ],
            ])
            ->add('reference', TextType::class, [
                'disabled' => true, // ✅ Désactiver le champ (généré automatiquement)
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('emplacement', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez l\'emplacement',
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
                ],
            ])
            ->add('description', null, ['attr' => ['class' => 'form-control']])
            ->add('nbr', null, ['attr' => ['class' => 'form-control']])
            ->add('dateAcquisition', DateType::class, [
                'widget' => 'single_text',
<<<<<<< HEAD
                'attr' => ['class' => 'form-control', 'value' => date('Y-m-d')],
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'جديد' => 'Neuf',
                    'حالة جيدة' => 'Bon(ne)',
                    'للتصليح' => 'A reparer',
                    'خارج الخدمة' => 'Hors service',
=======
                'attr' => ['class' => 'form-control'],
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Neuf' => 'neuf',
                    'Bon' => 'bon',
                    'À réparer' => 'à réparer',
                    'Hors service' => 'hors service',
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('etablissement', EntityType::class, [
<<<<<<< HEAD
    'class' => Etablissement::class,
    'choices' => $etablissements,
    'choice_label' => 'nom',
    'label' => 'المؤسسة',
    'attr' => ['class' => 'form-select'],
    'disabled' => !($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN')),
    'data' => $user->getEtablissement(), // ✅
    'required' => true,
])
        ;
=======
                'class' => Etablissement::class,
                'choices' => $etablissements,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un établissement',
                'attr' => ['class' => 'form-control select2-entity'],
            ])

            ;
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
