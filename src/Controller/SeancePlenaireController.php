<?php

namespace App\Controller;

use App\Entity\SeancePlenaire;
use App\Form\SeancePlenaireType;
use App\Repository\SeancePlenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/seance/plenaire')]
final class SeancePlenaireController extends AbstractController
{
    #[Route('/', name: 'app_seance_plenaire_index', methods: ['GET'])]
public function index(SeancePlenaireRepository $repository, Request $request): Response
{
    $query = $request->query->get('q');

    if ($query) {
        $seances = $repository->searchByAssociationOrDate($query);
    } else {
        $seances = $repository->findAll();
    }

    return $this->render('seance_plenaire/index.html.twig', [
        'seances' => $seances,
    ]);
}

    #[Route('/new', name: 'app_seance_plenaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $seancePlenaire = new SeancePlenaire();
        $form = $this->createForm(SeancePlenaireType::class, $seancePlenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($seancePlenaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_seance_plenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seance_plenaire/new.html.twig', [
            'seance_plenaire' => $seancePlenaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seance_plenaire_show', methods: ['GET'])]
    public function show(SeancePlenaire $seancePlenaire): Response
    {
        return $this->render('seance_plenaire/show.html.twig', [
            'seance_plenaire' => $seancePlenaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_seance_plenaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SeancePlenaire $seancePlenaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeancePlenaireType::class, $seancePlenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_seance_plenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seance_plenaire/edit.html.twig', [
            'seance_plenaire' => $seancePlenaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seance_plenaire_delete', methods: ['POST'])]
    public function delete(Request $request, SeancePlenaire $seancePlenaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seancePlenaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($seancePlenaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_seance_plenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
