<?php
// src/Controller/ReponseTechniqueController.php

namespace App\Controller;

use App\Entity\ReponseTechnique;
use App\Form\ReponseTechniqueType;
use App\Repository\ReponseTechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reponse/technique')]
class ReponseTechniqueController extends AbstractController
{
    #[Route('/', name: 'app_reponse_technique_index', methods: ['GET'])]
    public function index(ReponseTechniqueRepository $reponseTechniqueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('reponse_technique/index.html.twig', [
            'reponse_techniques' => $reponseTechniqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reponse_technique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $reponseTechnique = new ReponseTechnique();
        $form = $this->createForm(ReponseTechniqueType::class, $reponseTechnique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponseTechnique);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_technique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse_technique/new.html.twig', [
            'reponse_technique' => $reponseTechnique,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_technique_show', methods: ['GET'])]
    public function show(ReponseTechnique $reponseTechnique): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('reponse_technique/show.html.twig', [
            'reponse_technique' => $reponseTechnique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_technique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReponseTechnique $reponseTechnique, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $form = $this->createForm(ReponseTechniqueType::class, $reponseTechnique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_technique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse_technique/edit.html.twig', [
            'reponse_technique' => $reponseTechnique,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_reponse_technique_delete', methods: ['POST'])]
    public function delete(Request $request, ReponseTechnique $reponseTechnique, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        if ($this->isCsrfTokenValid('delete'.$reponseTechnique->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponseTechnique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_technique_index', [], Response::HTTP_SEE_OTHER);
    }
}