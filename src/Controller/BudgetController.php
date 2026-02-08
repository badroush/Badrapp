<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\Etablissement;
use App\Form\BudgetType;
use App\Repository\BudgetRepository;
use App\Repository\RessourceBudgetRepository;
use App\Repository\RessourceAffectationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\ActionControlService;

#[Route('/budget')]
class BudgetController extends AbstractController
{
     public function __construct(
        private ActionControlService $actionControlService
    ) {}
    #[Route('/', name: 'app_budget_index', methods: ['GET'])]
    public function index(
        BudgetRepository $budgetRepository,
        Security $security
    ): Response {
        $user = $security->getUser();
        // ADMIN & SUPER ADMIN → tout voir
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN')) {
            $budgets = $budgetRepository->findAll();
        } else {
            // FNC & DRJ → uniquement leur établissement
            $budgets = $budgetRepository->findBy([
                'idEtablissement' => $user->getEtablissement()
            ]);
        }

        // Calcul total par chapitre (optionnel mais conservé)
        $totalParChapitre = [];
        foreach ($budgets as $budget) {
            $chapitreId = $budget->getIdChapitre()->getId();
            $totalParChapitre[$chapitreId] =
                ($totalParChapitre[$chapitreId] ?? 0) + $budget->getMontant();
        }
        //dd($this->actionControlService);
        return $this->render('budget/index.html.twig', [
            'budgets' => $budgets,
            'total_par_chapitre' => $totalParChapitre,
            'action_control' => $this->actionControlService,
        ]);
    }

   #[Route('/new', name: 'app_budget_new', methods: ['GET', 'POST'])]
public function new(
    Request $request,
    EntityManagerInterface $em,
    Security $security,
    RessourceAffectationRepository $ressourceAffectationRepo,
    BudgetRepository $budgetRepo
): Response {
    $user = $security->getUser();
    $etablissement = $user->getEtablissement();

    if (!$etablissement) {
        throw $this->createAccessDeniedException(
            'Aucun établissement associé à votre compte.'
        );
    }

    $totalAffecte = $ressourceAffectationRepo
        ->getTotalAffecteParEtablissement($etablissement);

    $budgetsExistants = $budgetRepo
        ->getTotalBudgetsByEtablissement($etablissement);

    $budget = new Budget();
    $budget->setIdEtablissement($etablissement);
    $budget->setAnnee(date('Y'));
    $budget->setCreatedBy($user->getUserIdentifier());

    $form = $this->createForm(BudgetType::class, $budget);
    $form->handleRequest($request);

    $resteDisponible = $totalAffecte - $budgetsExistants;

    if ($form->isSubmitted() && $form->isValid()) {
        if ($budgetsExistants >= $totalAffecte) {
            $this->addFlash('error', 'لقد تم استنفاد المبلغ المخصص.');
            return $this->render('budget/new.html.twig', [
                'form' => $form->createView(),
                'total_affecte' => $totalAffecte,
                'budgets_existants' => $budgetsExistants,
                'reste_disponible' => $resteDisponible,
            ]);
        }

        // ✅ Vérifie si le chapitre existe déjà pour cet établissement dans la même année
        $existingBudget = $budgetRepo->findOneBy([
            'idEtablissement' => $etablissement,
            'idChapitre' => $budget->getIdChapitre(), 
            'annee' => $budget->getAnnee(), 
        ]);

        if ($existingBudget) {
            $this->addFlash('error', 'هذا الفصل موجود بالفعل في هذه السنة.');
            return $this->render('budget/new.html.twig', [
                'form' => $form->createView(),
                'total_affecte' => $totalAffecte,
                'budgets_existants' => $budgetsExistants,
                'reste_disponible' => $resteDisponible,
            ]);
        }
        $em->persist($budget);
        $em->flush();

        $this->addFlash('success', 'تم إنشاء الميزانية بنجاح.');
        return $this->redirectToRoute('app_budget_index');
    }

    return $this->render('budget/new.html.twig', [
        'form' => $form->createView(),
        'total_affecte' => $totalAffecte,
        'budgets_existants' => $budgetsExistants,
        'reste_disponible' => $resteDisponible,
    ]);
}

    #[Route('/{id}', name: 'app_budget_show', methods: ['GET'])]
    public function show(Budget $budget): Response
    {
        return $this->render('budget/show.html.twig', [
            'budget' => $budget,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_budget_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Budget $budget,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(BudgetType::class, $budget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_budget_index');
        }

        return $this->render('budget/edit.html.twig', [
            'budget' => $budget,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_budget_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Budget $budget,
        EntityManagerInterface $em
    ): Response {
        if ($this->isCsrfTokenValid(
            'delete' . $budget->getId(),
            $request->request->get('_token')
        )) {
            $em->remove($budget);
            $em->flush();
        }

        return $this->redirectToRoute('app_budget_index');
    }

    #[Route('/budget/{etablissement}/imprimer', name: 'app_budget_imprimer')]
public function imprimer(
    Etablissement $etablissement,
    BudgetRepository $budgetRepo,
    RessourceAffectationRepository $ressourceAffectationRepo 
): Response {
    $budgets = $budgetRepo->findBy(['idEtablissement' => $etablissement]);
    $ressources = $ressourceAffectationRepo->findBy(['idEtablissement' => $etablissement]); // ✅ Charger les affectations

    return $this->render('budget/imprimer.html.twig', [
        'etablissement' => $etablissement,
        'budgets' => $budgets,
        'ressources' => $ressources, 
    ]);
}
}
