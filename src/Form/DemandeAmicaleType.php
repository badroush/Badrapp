<?php

namespace App\Form;

use App\Entity\DemandeAmicale;
use App\Entity\OffreAmicale;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;


class DemandeAmicaleType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $etablissement = $user->getEtablissement();

        if (!$etablissement) {
            throw new \Exception('Utilisateur n\'a pas d\'établissement assigné.');
        }

        $builder
            ->add('offre', EntityType::class, [
                'class' => OffreAmicale::class,
                'choice_label' => 'nom',
                'label' => 'العرض',
                'query_builder' => fn($repo) => $repo->createQueryBuilder('o')
                    ->where('o.etat = :etat')
                    ->setParameter('etat', 'active'),
            ])
           ->add('beneficiaire', EntityType::class, [
    'class' => User::class,
    'choice_label' => 'nomp',
    'label' => 'المستفيد',
    'query_builder' => function (UserRepository $repo) use ($user) {
        $qb = $repo->createQueryBuilder('u');

        // Vérifier explicitement les rôles autorisés à voir tous les utilisateurs
        $rolesAutorises = ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'];
        $hasRoleAdmin = false;

        foreach ($rolesAutorises as $role) {
            if (in_array($role, $user->getRoles())) {
                $hasRoleAdmin = true;
                break;
            }
        }

        if ($hasRoleAdmin) {
            return $qb; // Voir tous les utilisateurs
        } else {
            // Limiter à l'établissement de l'utilisateur
            return $qb
                ->where('u.etablissement = :etab')
                ->setParameter('etab', $user->getEtablissement());
        }
    },
])
            ->add('commentaire', TextareaType::class, [
                'label' => 'ملاحظات إضافية',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeAmicale::class,
        ]);
    }
}
