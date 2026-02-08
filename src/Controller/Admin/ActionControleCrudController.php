<?php

namespace App\Controller\Admin;

use App\Entity\ActionControle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ActionControleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ActionControle::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('action', 'Action');
        
        yield ChoiceField::new('roles', 'Rôles')
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
            ->allowMultipleChoices() // ✅ Autoriser plusieurs choix
            ->setRequired(true);

        yield BooleanField::new('active', 'Active');
        yield BooleanField::new('masque', 'Masqué');
    }
    
}
