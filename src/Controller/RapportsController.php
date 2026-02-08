<?php

namespace App\Controller;

use App\Repository\PanierAchatCollectifRepository;
use App\Repository\BudgetRepository;
use App\Repository\UserRepository;
use App\Repository\EtablissementRepository;
use App\Repository\ChapitreAchatsCollectifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/rapports')]
class RapportsController extends AbstractController
{
    public function __construct(
        private PanierAchatCollectifRepository $panierRepo,
        private BudgetRepository $budgetRepo,
        private UserRepository $userRepo,
        private EtablissementRepository $etabRepo,
        private ChapitreAchatsCollectifRepository $chapitreRepo,
    ) {
    }

    #[Route('/', name: 'app_rapports_index')]
    public function index(): Response
    {
        $user=$this->getUser();
        $etablissement=$user->getEtablissement();
        return $this->render('rapports/index.html.twig', [
            'etablissement' => $etablissement
        ]);
    }



    #[Route('/utilisateurs', name: 'app_rapports_utilisateurs')]
    public function utilisateurs(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $users = $this->userRepo->findAll();

        return $this->render('rapports/utilisateurs.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/etablissements', name: 'app_rapports_etablissements')]
    public function etablissements(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $etablissements = $this->etabRepo->findAll();

        return $this->render('rapports/etablissements.html.twig', [
            'etablissements' => $etablissements,
        ]);
    }

    #[Route('/chapitres', name: 'app_rapports_chapitres')]
    public function chapitres(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $chapitres = $this->chapitreRepo->findAll();

        return $this->render('rapports/chapitres.html.twig', [
            'chapitres' => $chapitres,
        ]);
    }

#[Route('/etablissements/budget', name: 'app_rapports_budget_etablissement')]
public function budgetEtablissement(Request $request): Response
{

    $user = $this->getUser();
    $etablissement = $user->getEtablissement();

    // ✅ Vérifier les rôles pour afficher tous les établissements
    if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
        $etablissements = $this->etabRepo->findAll();
        $etablissementId = $request->query->get('etablissement');

        if ($etablissementId) {
            $etablissement = $this->etabRepo->find($etablissementId);
        }
    } else {
        $etablissements = [$etablissement];
    }

    $budgets = $this->budgetRepo->findBy(['idEtablissement' => $etablissement]);

    return $this->render('rapports/budget_etablissement.html.twig', [
        'etablissements' => $etablissements,
        'etablissement' => $etablissement,
        'budgets' => $budgets,
    ]);
}
#[Route('/budget-global', name: 'app_rapports_budget_global')]
public function budgetGlobal(): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
    $budgets = $this->panierRepo->getBudgetsGlobal();

    return $this->render('rapports/budget_global.html.twig', [
        'budgets' => $budgets,
    ]);
}

}