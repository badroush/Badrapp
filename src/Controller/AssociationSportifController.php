<?php

namespace App\Controller;

use App\Entity\AssociationSportif;
use App\Form\AssociationSportifType;
use App\Repository\AssociationSportifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/association/sportif')]
final class AssociationSportifController extends AbstractController
{
    #[Route('/', name: 'app_association_sportif_index', methods: ['GET'])]
public function index(AssociationSportifRepository $repository, Request $request): Response
{
    $query = $request->query->get('q');

    if ($query) {
        $associations = $repository->searchByNameOrDelegation($query);
    } else {
        $associations = $repository->findAll();
    }

    return $this->render('association_sportif/index.html.twig', [
        'associations' => $associations,
    ]);
}

    #[Route('/new', name: 'app_association_sportif_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $associationSportif = new AssociationSportif();
        $form = $this->createForm(AssociationSportifType::class, $associationSportif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($associationSportif);
            $entityManager->flush();

            return $this->redirectToRoute('app_association_sportif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('association_sportif/new.html.twig', [
            'association_sportif' => $associationSportif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_association_sportif_show', methods: ['GET'])]
    public function show(AssociationSportif $associationSportif): Response
    {
        return $this->render('association_sportif/show.html.twig', [
            'association_sportif' => $associationSportif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_association_sportif_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AssociationSportif $associationSportif, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssociationSportifType::class, $associationSportif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_association_sportif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('association_sportif/edit.html.twig', [
            'association_sportif' => $associationSportif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_association_sportif_delete', methods: ['POST'])]
    public function delete(Request $request, AssociationSportif $associationSportif, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$associationSportif->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($associationSportif);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_association_sportif_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
