<?php
// src/Controller/ConnexionInternetController.php

namespace App\Controller;

use App\Entity\ConnexionInternet;
use App\Form\ConnexionInternetType;
use App\Repository\ConnexionInternetRepository;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/connexion/internet')]
class ConnexionInternetController extends AbstractController
{
    #[Route('/', name: 'app_connexion_internet_index', methods: ['GET'])]
    public function index(ConnexionInternetRepository $connexionInternetRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('connexion_internet/index.html.twig', [
            'connexion_internets' => $connexionInternetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_connexion_internet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $connexionInternet = new ConnexionInternet();
        $form = $this->createForm(ConnexionInternetType::class, $connexionInternet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($connexionInternet);
            $entityManager->flush();

            return $this->redirectToRoute('app_connexion_internet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('connexion_internet/new.html.twig', [
            'connexion_internet' => $connexionInternet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_connexion_internet_show', methods: ['GET'])]
    public function show(ConnexionInternet $connexionInternet): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('connexion_internet/show.html.twig', [
            'connexion_internet' => $connexionInternet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_connexion_internet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ConnexionInternet $connexionInternet, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $form = $this->createForm(ConnexionInternetType::class, $connexionInternet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // ✅ flush() suffit pour les modifications

            return $this->redirectToRoute('app_connexion_internet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('connexion_internet/edit.html.twig', [
            'connexion_internet' => $connexionInternet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_connexion_internet_delete', methods: ['POST'])]
    public function delete(Request $request, ConnexionInternet $connexionInternet, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        if ($this->isCsrfTokenValid('delete'.$connexionInternet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($connexionInternet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_connexion_internet_index', [], Response::HTTP_SEE_OTHER);
    }
}