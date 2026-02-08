<?php

namespace App\Controller;

use App\Entity\ChapitreBudget;
use App\Form\ChapitreBudgetType;
use App\Repository\ChapitreBudgetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use app\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

// grant to Admin
#[Route('/chapitre-budget')]
class ChapitreBudgetController extends AbstractController
{
    #[Route('/', name: 'app_chapitre_budget_index', methods: ['GET'])]
    public function index(ChapitreBudgetRepository $chapitreBudgetRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('chapitre_budget/index.html.twig', [
            'chapitre_budgets' => $chapitreBudgetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chapitre_budget_new', methods: ['GET', 'POST'])]
    public function new( Request $request,EntityManagerInterface $entityManager): Response {
            $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
    $chapitreBudget = new ChapitreBudget();
    $form = $this->createForm(ChapitreBudgetType::class, $chapitreBudget);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($chapitreBudget);
        $entityManager->flush();
        return $this->redirectToRoute('app_chapitre_budget_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('chapitre_budget/new.html.twig', [
        'chapitre_budget' => $chapitreBudget,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_chapitre_budget_show', methods: ['GET'])]
    public function show(ChapitreBudget $chapitreBudget): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('chapitre_budget/show.html.twig', [
            'chapitre_budget' => $chapitreBudget,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chapitre_budget_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ChapitreBudget $chapitreBudget, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $form = $this->createForm(ChapitreBudgetType::class, $chapitreBudget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chapitreBudget); // ✅ Correct
            $entityManager->flush();           // ✅ Correct

            return $this->redirectToRoute('app_chapitre_budget_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chapitre_budget/edit.html.twig', [
            'chapitre_budget' => $chapitreBudget,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chapitre_budget_delete', methods: ['POST'])]
    public function delete(Request $request, ChapitreBudget $chapitreBudget, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $user = $this->getUser();

        // Vérifier que l'utilisateur a le droit de supprimer ce matériel
        if ($user->isSousAdmin() && $chapitreBudget->getEtablissement() !== $user->getEtablissement()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce matériel.');
        }

        if ($this->isCsrfTokenValid('delete'.$chapitreBudget->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chapitreBudget);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_chapitre_budget_index', [], Response::HTTP_SEE_OTHER);
    }
}