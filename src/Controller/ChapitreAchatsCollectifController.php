<?php

namespace App\Controller;

use App\Entity\ChapitreAchatsCollectif;
use App\Form\ChapitreAchatsCollectifType;
use App\Repository\ChapitreAchatsCollectifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use renderform


#[Route('/chapitre/achats/collectif')]
class ChapitreAchatsCollectifController extends AbstractController
{
    
    #[Route('/', name: 'app_chapitre_achats_collectif_index', methods: ['GET'])]
    public function index(ChapitreAchatsCollectifRepository $chapitreAchatsCollectifRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('chapitre_achats_collectif/index.html.twig', [
            'chapitre_achats_collectifs' => $chapitreAchatsCollectifRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chapitre_achats_collectif_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $chapitreAchatsCollectif = new ChapitreAchatsCollectif();
        $form = $this->createForm(ChapitreAchatsCollectifType::class, $chapitreAchatsCollectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chapitreAchatsCollectif);
            $entityManager->flush();

            return $this->redirectToRoute('app_chapitre_achats_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chapitre_achats_collectif/new.html.twig', [
            'chapitre_achats_collectif' => $chapitreAchatsCollectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chapitre_achats_collectif_show', methods: ['GET'])]
    public function show(ChapitreAchatsCollectif $chapitreAchatsCollectif): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('chapitre_achats_collectif/show.html.twig', [
            'chapitre_achats_collectif' => $chapitreAchatsCollectif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chapitre_achats_collectif_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ChapitreAchatsCollectif $chapitreAchatsCollectif, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $form = $this->createForm(ChapitreAchatsCollectifType::class, $chapitreAchatsCollectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chapitre_achats_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chapitre_achats_collectif/edit.html.twig', [
            'chapitre_achats_collectif' => $chapitreAchatsCollectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chapitre_achats_collectif_delete', methods: ['POST'])]
    public function delete(Request $request, ChapitreAchatsCollectif $chapitreAchatsCollectif, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        if ($this->isCsrfTokenValid('delete'.$chapitreAchatsCollectif->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chapitreAchatsCollectif);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chapitre_achats_collectif_index', [], Response::HTTP_SEE_OTHER);
    }
}