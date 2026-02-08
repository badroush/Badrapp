<?php

namespace App\Controller;

use App\Entity\DocumentSeance;
use App\Form\DocumentSeanceType;
use App\Repository\DocumentSeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/document/seance')]
final class DocumentSeanceController extends AbstractController
{
    #[Route(name: 'app_document_seance_index', methods: ['GET'])]
    public function index(DocumentSeanceRepository $documentSeanceRepository): Response
    {
        return $this->render('document_seance/index.html.twig', [
            'document_seances' => $documentSeanceRepository->findAll(),
        ]);
    }

   #[Route('/new', name: 'app_document_seance_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
{
    $documentSeance = new DocumentSeance();
    $form = $this->createForm(DocumentSeanceType::class, $documentSeance);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $fichier = $form->get('fichier')->getData();

        if ($fichier) {
            $fileName = $fileUploader->upload($fichier);
            $documentSeance->setFichier($fileName);
        }

        $entityManager->persist($documentSeance);
        $entityManager->flush();

        return $this->redirectToRoute('app_document_seance_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('document_seance/new.html.twig', [
        'document_seance' => $documentSeance,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_document_seance_show', methods: ['GET'])]
    public function show(DocumentSeance $documentSeance): Response
    {
        return $this->render('document_seance/show.html.twig', [
            'document_seance' => $documentSeance,
        ]);
    }

  #[Route('/{id}/edit', name: 'app_document_seance_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, DocumentSeance $documentSeance, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
{
    $form = $this->createForm(DocumentSeanceType::class, $documentSeance);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $fichier = $form->get('fichier')->getData();

        if ($fichier) {
            $fileName = $fileUploader->upload($fichier);
            $documentSeance->setFichier($fileName);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_document_seance_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('document_seance/edit.html.twig', [
        'document_seance' => $documentSeance,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_document_seance_delete', methods: ['POST'])]
public function delete(Request $request, DocumentSeance $documentSeance, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
{
    if ($this->isCsrfTokenValid('delete'.$documentSeance->getId(), $request->request->get('_token'))) {
        // Supprimer le fichier physique
        if ($documentSeance->getFichier()) {
            $filePath = $this->getParameter('kernel.project_dir').'/public/uploads/documents/'.$documentSeance->getFichier();
            if (file_exists($filePath)) {
                $filesystem->remove($filePath);
            }
        }

        $entityManager->remove($documentSeance);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_document_seance_index', [], Response::HTTP_SEE_OTHER);
}
}
