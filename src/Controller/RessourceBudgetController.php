<?php

namespace App\Controller;

use App\Entity\RessourceBudget;
use App\Form\RessourceBudgetType;
use App\Repository\RessourceBudgetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/ressource-budget')]
class RessourceBudgetController extends AbstractController
{
    #[Route('/', name: 'app_ressource_budget_index', methods: ['GET'])]
    public function index(RessourceBudgetRepository $ressourceBudgetRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('ressource_budget/index.html.twig', [
            'ressource_budgets' => $ressourceBudgetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ressource_budget_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $ressourceBudget = new RessourceBudget();
        $form = $this->createForm(RessourceBudgetType::class, $ressourceBudget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $entityManager->persist($ressourceBudget);
            $entityManager->flush();
            return $this->redirectToRoute('app_ressource_budget_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ressource_budget/new.html.twig', [
            'ressource_budget' => $ressourceBudget,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ressource_budget_show', methods: ['GET'])]
    public function show(RessourceBudget $ressourceBudget): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('ressource_budget/show.html.twig', [
            'ressource_budget' => $ressourceBudget,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ressource_budget_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RessourceBudget $ressourceBudget, RessourceBudgetRepository $ressourceBudgetRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $form = $this->createForm(RessourceBudgetType::class, $ressourceBudget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ressourceBudget);
            $entityManager->flush();

            return $this->redirectToRoute('app_ressource_budget_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ressource_budget/edit.html.twig', [
            'ressource_budget' => $ressourceBudget,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ressource_budget_delete', methods: ['POST'])]
    public function delete(Request $request, RessourceBudget $ressourceBudget,EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $user = $this->getUser();
        if ($user->isSousAdmin() && $ressourceBudget->getEtablissement() !== $user->getEtablissement()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce matériel.');
        }
        if ($this->isCsrfTokenValid('delete'.$ressourceBudget->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ressourceBudget);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_ressource_budget_index', [], Response::HTTP_SEE_OTHER);
    }
}