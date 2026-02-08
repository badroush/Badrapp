<?php

namespace App\Controller;

use App\Entity\DetailAchatsCollectif;
use App\Form\DetailAchatsCollectifType;
use App\Repository\DetailAchatsCollectifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/detail/achats/collectif')]
class DetailAchatsCollectifController extends AbstractController
{
    
    #[Route('/', name: 'app_detail_achats_collectif_index', methods: ['GET'])]
    public function index(DetailAchatsCollectifRepository $detailAchatsCollectifRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('detail_achats_collectif/index.html.twig', [
            'detail_achats_collectifs' => $detailAchatsCollectifRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_detail_achats_collectif_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $detailAchatsCollectif = new DetailAchatsCollectif();
        $form = $this->createForm(DetailAchatsCollectifType::class, $detailAchatsCollectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($detailAchatsCollectif);
            $entityManager->flush();

            return $this->redirectToRoute('app_detail_achats_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('detail_achats_collectif/new.html.twig', [
            'detail_achats_collectif' => $detailAchatsCollectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_detail_achats_collectif_show', methods: ['GET'])]
    public function show(DetailAchatsCollectif $detailAchatsCollectif): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('detail_achats_collectif/show.html.twig', [
            'detail_achats_collectif' => $detailAchatsCollectif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_detail_achats_collectif_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DetailAchatsCollectif $detailAchatsCollectif, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $form = $this->createForm(DetailAchatsCollectifType::class, $detailAchatsCollectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_detail_achats_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('detail_achats_collectif/edit.html.twig', [
            'detail_achats_collectif' => $detailAchatsCollectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_detail_achats_collectif_delete', methods: ['POST'])]
    public function delete(Request $request, DetailAchatsCollectif $detailAchatsCollectif, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        if ($this->isCsrfTokenValid('delete'.$detailAchatsCollectif->getId(), $request->request->get('_token'))) {
            $entityManager->remove($detailAchatsCollectif);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_detail_achats_collectif_index', [], Response::HTTP_SEE_OTHER);
    }
}