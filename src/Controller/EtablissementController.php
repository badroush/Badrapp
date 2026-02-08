<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Form\EtablissementType;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/etablissement')]
#[IsGranted('ROLE_ADMIN')] // Tous les utilisateurs connectés peuvent voir
class EtablissementController extends AbstractController
{
<<<<<<< HEAD

    #[Route('/', name: 'etablissement_index', methods: ['GET'])]
    public function index(EtablissementRepository $etablissementRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
=======
    #[Route('/', name: 'etablissement_index', methods: ['GET'])]
    public function index(EtablissementRepository $etablissementRepo): Response
    {
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        // Seul l'admin principal peut voir tous les établissements
        if ($this->getUser()->isSousAdmin()) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        return $this->render('etablissement/index.html.twig', [
            'etablissements' => $etablissementRepo->findAll(),
        ]);
    }

    #[Route('/new', name: 'etablissement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
<<<<<<< HEAD
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        // Seul l'admin principal peut créer des établissements
        if ($this->getUser()->isSousAdmin()) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etablissement);
            $entityManager->flush();

            return $this->redirectToRoute('etablissement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etablissement/new.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'etablissement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager): Response
    {
<<<<<<< HEAD
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        // Seul l'admin principal peut modifier un établissement
        if ($this->getUser()->isSousAdmin()) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('etablissement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etablissement/edit.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'etablissement_delete', methods: ['POST'])]
    public function delete(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager): Response
    {
<<<<<<< HEAD
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        // Seul l'admin principal peut supprimer un établissement
        if ($this->getUser()->isSousAdmin()) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        if ($this->isCsrfTokenValid('delete'.$etablissement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($etablissement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('etablissement_index', [], Response::HTTP_SEE_OTHER);
    }
}
