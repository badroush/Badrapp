<?php

namespace App\Controller;

use App\Entity\PanierAchatCollectif;
use App\Form\PanierAchatCollectifType;
use App\Repository\PanierAchatCollectifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier/achat/collectif')]
class PanierAchatCollectifController extends AbstractController
{
    #[Route('/', name: 'app_panier_achat_collectif_index', methods: ['GET'])]
    public function index(PanierAchatCollectifRepository $panierAchatCollectifRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('panier_achat_collectif/index.html.twig', [
            'panier_achat_collectifs' => $panierAchatCollectifRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_panier_achat_collectif_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panierAchatCollectif = new PanierAchatCollectif();
        $form = $this->createForm(PanierAchatCollectifType::class, $panierAchatCollectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panierAchatCollectif);
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_achat_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier_achat_collectif/new.html.twig', [
            'panier_achat_collectif' => $panierAchatCollectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_achat_collectif_show', methods: ['GET'])]
    public function show(PanierAchatCollectif $panierAchatCollectif): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('panier_achat_collectif/show.html.twig', [
            'panier_achat_collectif' => $panierAchatCollectif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_achat_collectif_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PanierAchatCollectif $panierAchatCollectif, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $form = $this->createForm(PanierAchatCollectifType::class, $panierAchatCollectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_achat_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier_achat_collectif/edit.html.twig', [
            'panier_achat_collectif' => $panierAchatCollectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_achat_collectif_delete', methods: ['POST'])]
    public function delete(Request $request, PanierAchatCollectif $panierAchatCollectif, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        if ($this->isCsrfTokenValid('delete'.$panierAchatCollectif->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panierAchatCollectif);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_achat_collectif_index', [], Response::HTTP_SEE_OTHER);
    }
}