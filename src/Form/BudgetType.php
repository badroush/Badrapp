<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\ChapitreBudget;
use App\Entity\Etablissement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class BudgetType extends AbstractType
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
            ->add('idChapitre', EntityType::class, [
                'class' => ChapitreBudget::class,
                'choice_label' => 'nom',
                'label' => 'الفصل',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('idEtablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choices' => $etablissements, 
                'choice_label' => 'nom',
                'label' => 'المؤسسة',
                'attr' => ['class' => 'form-select'],
                'disabled' => !in_array('ROLE_ADMIN', $user->getRoles()) && !in_array ('ROLE_SUPER_ADMIN', $user->getRoles()),
            ])
            ->add('montant', NumberType::class, [
                'label' => 'المبلغ',
                'attr' => ['class' => 'form-control'], 
            ])
            ->add('annee', IntegerType::class, [
                'label' => 'السنة المالية',
                'attr' => ['class' => 'form-control','value' => date('Y')], 
                'disabled' => !in_array('ROLE_ADMIN', $user->getRoles()) && !in_array ('ROLE_SUPER_ADMIN', $user->getRoles()),

            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Budget::class,
        ]);
    }
}