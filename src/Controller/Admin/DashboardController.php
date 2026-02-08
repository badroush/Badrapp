<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Materiel;
use App\Entity\Etablissement;
<<<<<<< HEAD
use App\Entity\Grade;
use App\Entity\ControleBouton;
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
<<<<<<< HEAD
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\LibelleMateriel;
use App\Entity\ActionControle;
use App\Entity\Delegation;
use App\Entity\DocumentSeance;
use App\Entity\ClasseSportif;

=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa



#[IsGranted('ROLE_ADMIN')]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
<<<<<<< HEAD
    ) {}

    public function index(): Response
    {
        return $this->redirect($this->generateUrl('admin_user_index'));
    }
=======
    ) {
    }
    #[Route('/admin', name: 'admin')]
public function index(): Response
{
    return $this->redirect($this->generateUrl('admin_user_index'));
}
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Badrapp Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Retour au site', 'fa fa-undo', $this->urlGenerator->generate('app_home'));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Établissements', 'fa fa-building', Etablissement::class);
        yield MenuItem::linkToCrud('Matériel', 'fa fa-laptop', Materiel::class);
<<<<<<< HEAD
        yield MenuItem::linkToCrud('Grades', 'fa fa-star', Grade::class);
        yield MenuItem::linkToCrud('LibelleMateriel', 'fa fa-button', LibelleMateriel::class);
        yield MenuItem::linkToCrud('Contrôle Boutons', 'fa fa-cogs', ActionControle::class);
        yield MenuItem::linkToCrud('Delegation', 'fa fa-users', Delegation::class);
        yield MenuItem::linkToCrud('Documents de séance', 'fa fa-cogs', DocumentSeance::class);
        yield MenuItem::linkToCrud('classe sportif', 'fa fa-cogs', ClasseSportif::class);

        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return UserMenu::new()
            ->setName($user->getUserIdentifier())
            ->setAvatarUrl(null);
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    }
}
