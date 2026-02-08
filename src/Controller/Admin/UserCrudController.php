<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Etablissement;
<<<<<<< HEAD
use App\Entity\Grade;
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
<<<<<<< HEAD
{
    $email = EmailField::new('email', 'العنوان الألكتروني');
    $nomp = TextField::new('nomp', 'Nom');
    $matricule = TextField::new('matricule', 'Matricule');
    $cin = TextField::new('cin', 'CIN');
    $grade = AssociationField::new('grade', 'Grade')
        ->setFormTypeOptions([
            'class' => Grade::class,
        ])
        ->setRequired(false);

    $password = TextField::new('password', 'كلمة المرور')
        ->setFormTypeOption('required', false) 
        ->setHelp('اترك فارغاً لاستخدام كلمة المرور الحالية');
    $isVerified = BooleanField::new('isVerified', 'معتمد؟');
    $isSousAdmin = BooleanField::new('isSousAdmin', 'Sous-admin ?')
        ->setFormTypeOption('required', false);
    $etablissement = AssociationField::new('etablissement', 'المؤسسة')
        ->setFormTypeOption('required', false)
        ->setQueryBuilder(function ($queryBuilder) {
            return $queryBuilder->orderBy('entity.nom', 'ASC');
        });
    $roles = ChoiceField::new('roles', 'الصلاحيات')
        ->setChoices([
            'Utilisateur' => 'ROLE_USER',
            'Sous-admin' => 'ROLE_SOUS_ADMIN',
            'Admin' => 'ROLE_ADMIN',
            'Super Admin' => 'ROLE_SUPER_ADMIN',
            'Directeur Jeunesse' => 'ROLE_DRJ',
            'Directeur Sport' => 'ROLE_DRS',
            'Magasinier' => 'ROLE_MAG',
            'Financier' => 'ROLE_FNC',
            'Technicien' => 'ROLE_TECH',
        ])
        ->allowMultipleChoices()
        ->setFormTypeOption('required', false);

    if ('new' === $pageName) {
        return [$email, $nomp, $matricule, $cin, $grade, $password, $isVerified, $isSousAdmin, $etablissement, $roles];
    }

    return [$email, $nomp, $matricule, $cin, $grade, $password, $isVerified, $isSousAdmin, $etablissement, $roles];
}
=======
    {
        $email = EmailField::new('email', 'العنوان الألكتروني');
        $nomp= TextField::new('nomp', 'Nom');
        $password = TextField::new('password', 'كلمة المرور')
            ->setFormTypeOption('required', false) // Mot de passe optionnel lors de l'édition
            ->setHelp('اترك فارغاً لاستخدام كلمة المرور الحالية');
        $isVerified = BooleanField::new('isVerified', 'معتمد؟');
        $isSousAdmin = BooleanField::new('isSousAdmin', 'Sous-admin ?')
            ->setFormTypeOption('required', false);
        $etablissement = AssociationField::new('etablissement', 'المؤسسة')
            ->setFormTypeOption('required', false)
            ->setQueryBuilder(function ($queryBuilder) {
                return $queryBuilder->orderBy('entity.nom', 'ASC');
            });
        $roles = ChoiceField::new('roles', 'الصلاحيات')
            ->setChoices([
                'Utilisateur' => 'ROLE_USER',
                'Sous-admin' => 'ROLE_SOUS_ADMIN',
                'Admin' => 'ROLE_ADMIN',
                'Super Admin' => 'ROLE_SUPER_ADMIN',
            ])
            ->allowMultipleChoices()
            ->setFormTypeOption('required', false);

        if ('new' === $pageName) {
            return [$email,$nomp, $password, $isVerified, $isSousAdmin, $etablissement, $roles];
        }

        return [$email, $nomp, $password, $isVerified, $isSousAdmin, $etablissement, $roles];
    }

>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            // Hacher le mot de passe
            $plainPassword = $entityInstance->getPassword();
            if ($plainPassword) {
                $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
                $entityInstance->setPassword($hashedPassword);
            }

            // S'assurer que le rôle ROLE_USER est toujours présent
            $roles = $entityInstance->getRoles();
            if (!in_array('ROLE_USER', $roles)) {
                $roles[] = 'ROLE_USER';
                $entityInstance->setRoles(array_unique($roles));
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            // Hacher le mot de passe s'il est modifié
            $plainPassword = $entityInstance->getPassword();
            if ($plainPassword) {
                $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
                $entityInstance->setPassword($hashedPassword);
            }

            // S'assurer que le rôle ROLE_USER est toujours présent
            $roles = $entityInstance->getRoles();
            if (!in_array('ROLE_USER', $roles)) {
                $roles[] = 'ROLE_USER';
                $entityInstance->setRoles(array_unique($roles));
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
