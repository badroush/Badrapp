<?php

namespace App\Controller;

use App\Entity\ItemPanierAchatCollectif;
use App\Form\ItemPanierAchatCollectifType;
use App\Repository\ItemPanierAchatCollectifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/item/panier/achat/collectif')]
class ItemPanierAchatCollectifController extends AbstractController
{
    #[Route('/', name: 'app_item_panier_achat_collectif_index', methods: ['GET'])]
    public function index(ItemPanierAchatCollectifRepository $itemPanierAchatCollectifRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('item_panier_achat_collectif/index.html.twig', [
            'item_panier_achat_collectifs' => $itemPanierAchatCollectifRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_item_panier_achat_collectif_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $itemPanierAchatCollectif = new ItemPanierAchatCollectif();
        $form = $this->createForm(ItemPanierAchatCollectifType::class, $itemPanierAchatCollectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($itemPanierAchatCollectif);
            $entityManager->flush();

            return $this->redirectToRoute('app_item_panier_achat_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item_panier_achat_collectif/new.html.twig', [
            'item_panier_achat_collectif' => $itemPanierAchatCollectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_item_panier_achat_collectif_show', methods: ['GET'])]
    public function show(ItemPanierAchatCollectif $itemPanierAchatCollectif): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('item_panier_achat_collectif/show.html.twig', [
            'item_panier_achat_collectif' => $itemPanierAchatCollectif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_item_panier_achat_collectif_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ItemPanierAchatCollectif $itemPanierAchatCollectif, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $form = $this->createForm(ItemPanierAchatCollectifType::class, $itemPanierAchatCollectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_item_panier_achat_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item_panier_achat_collectif/edit.html.twig', [
            'item_panier_achat_collectif' => $itemPanierAchatCollectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_item_panier_achat_collectif_delete', methods: ['POST'])]
    public function delete(Request $request, ItemPanierAchatCollectif $itemPanierAchatCollectif, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        if ($this->isCsrfTokenValid('delete'.$itemPanierAchatCollectif->getId(), $request->request->get('_token'))) {
            $entityManager->remove($itemPanierAchatCollectif);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_item_panier_achat_collectif_index', [], Response::HTTP_SEE_OTHER);
    }
}