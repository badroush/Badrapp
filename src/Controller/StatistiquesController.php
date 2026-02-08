<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\EtablissementRepository;
use App\Repository\BudgetRepository;
use App\Repository\ChapitreAchatsCollectifRepository;
use App\Repository\PanierAchatCollectifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/statistiques')]
class StatistiquesController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EtablissementRepository $etablissementRepository,
        private BudgetRepository $budgetRepository,
        private ChapitreAchatsCollectifRepository $chapitreRepo,
        private PanierAchatCollectifRepository $panierRepo,
    ) {
    }

    #[Route('/', name: 'app_statistiques_index')]
public function index(): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    // Statistiques générales
    $totalUsers = $this->userRepository->count([]);
    $totalEtablissements = $this->etablissementRepository->count([]);
    $totalBudgets = $this->budgetRepository->count([]);
    $totalChapitres = $this->chapitreRepo->count([]);
    $totalCommandes = $this->panierRepo->count([]);
    $totalAchats = $this->panierRepo->getTotalAchats();

    // Statistiques par rôle
    $usersByRole = $this->userRepository->getUsersCountByRole();

    // Statistiques par établissement
    $usersByEtablissement = $this->userRepository->getUsersCountByEtablissement();

    // Montant des achats par établissement
    $achatsByEtablissement = $this->panierRepo->getTotalAchatsByEtablissement();

    // Montant des achats par chapitre
    $achatsByChapitre = $this->panierRepo->getTotalAchatsByChapitre();

    // Évolution mensuelle des achats (cette année)
    $achatsMensuels = $this->panierRepo->getAchatsMensuels();

    // Budgets les plus utilisés
    $budgetsUtilises = $this->panierRepo->getBudgetsUtilises();

    return $this->render('statistiques/index.html.twig', [
        'totalUsers' => $totalUsers,
        'totalEtablissements' => $totalEtablissements,
        'totalBudgets' => $totalBudgets,
        'totalChapitres' => $totalChapitres,
        'totalCommandes' => $totalCommandes,
        'totalAchats' => $totalAchats,
        'usersByRole' => $usersByRole,
        'usersByEtablissement' => $usersByEtablissement,
        'achatsByEtablissement' => $achatsByEtablissement,
        'achatsByChapitre' => $achatsByChapitre,
        'achatsMensuels' => $achatsMensuels,
        'budgetsUtilises' => $budgetsUtilises,
    ]);
}
}