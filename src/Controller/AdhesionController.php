<?php

namespace App\Controller;

use App\Entity\Adhesion;
use App\Form\AdhesionType;
use App\Repository\AdhesionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/adhesion')]
final class AdhesionController extends AbstractController
{
    #[Route('/adhesion', name: 'adhesion_index', methods: ['GET'])]
public function index(AdhesionRepository $adhesionRepository): Response
{
    $user = $this->getUser();

    if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN')) {
        $adhesions = $adhesionRepository->findBy(['valider' => true]);
    } else {
        $etablissement = $user->getEtablissement();
        $adhesions = $adhesionRepository->findBy(['etablissement' => $etablissement]);
    }

    return $this->render('adhesion/index.html.twig', [
        'adhesions' => $adhesions,
    ]);
}

#[Route('/adhesion/new', name: 'adhesion_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $adhesion = new Adhesion();
    $user = $this->getUser();

    // Si l'utilisateur n'est pas admin, associer automatiquement son établissement
    if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
        $adhesion->setEtablissement($user->getEtablissement());
        $isAdmin = false;
    } else {
        $isAdmin = true;
    }

    $form = $this->createForm(AdhesionType::class, $adhesion, ['is_admin' => $isAdmin]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photo')->getData();

        if ($photoFile) {
            $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $fileName = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

            try {
                $photoFile->move($this->getParameter('photos_directory'), $fileName);
                $adhesion->setPhoto($fileName);
            } catch (FileException $e) {
                // Gérer l'erreur
            }
        }

        $entityManager->persist($adhesion);
        $entityManager->flush();

        return $this->redirectToRoute('adhesion_index');
    }

    return $this->render('adhesion/new.html.twig', [
        'adhesion' => $adhesion,
        'form' => $form,
    ]);
}

    #[Route('/{id}/edit', name: 'app_adhesion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {

    $this->denyAccessUnlessGranted('edit', $adhesion); // Sécurité
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adhesion/edit.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adhesion_delete', methods: ['POST'])]
    public function delete(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $adhesion); // Sécurité
        if ($this->isCsrfTokenValid('delete'.$adhesion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adhesion_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/adhesion/{id}/imprimer', name: 'adhesion_imprimer', methods: ['GET'])]
public function imprimer(Adhesion $adhesion): Response
{
    $this->denyAccessUnlessGranted('view', $adhesion); // Vérifie l'accès à l'adhésion
    if (!$adhesion->getEtablissement()) {
        throw $this->createNotFoundException('L\'adhésion n\'est associée à aucun établissement.');
    }
    return $this->render('adhesion/imprimer.html.twig', [
        'adhesion' => $adhesion,
        'etablissement' => $adhesion->getEtablissement(),
    ]);
}

#[Route('/adhesion/{id}', name: 'adhesion_show', methods: ['GET'])]
public function show(Adhesion $adhesion): Response
{
    $this->denyAccessUnlessGranted('view', $adhesion);

    return $this->render('adhesion/show.html.twig', [
        'adhesion' => $adhesion,
    ]);
}
#[Route('/adhesion/{id}/carte', name: 'adhesion_carte', methods: ['GET'])]
public function carte(Adhesion $adhesion): Response
{
    // Utilise le Voter pour vérifier l'accès
    $this->denyAccessUnlessGranted('view', $adhesion);

    return $this->render('adhesion/carte.html.twig', [
        'adhesion' => $adhesion,
        'etablissement' => $adhesion->getEtablissement(),
    ]);
}

#[Route('/adhesion-a-imprimer', name: 'adhesion_a_imprimer')]
public function aImprimer(AdhesionRepository $adhesionRepository): Response
{ 
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // Afficher tous les participants validés (imprimés ou non)
    $adhesions = $adhesionRepository->findBy(['valider' => true]);

    return $this->render('adhesion/index.html.twig', [
        'adhesions' => $adhesions,
    ]);
}
#[Route('/adhesion/{id}/imprimer', name: 'adhesion_imprimer')]
public function imprimer2(Adhesion $adhesion, EntityManagerInterface $em): Response
{
    $impr=$adhesion->getImprimer();
    if ($impr !== null) {
        $adhesion->setImprimer(null);
        $em->flush();
        return $this->redirectToRoute('adhesion_a_imprimer');
    }
    $adhesion->setImprimer(new \DateTime());
    $em->flush();

    return $this->redirectToRoute('adhesion_a_imprimer');
}
#[Route('/adhesion/{id}/valider', name: 'adhesion_valider')]
public function valider(Adhesion $adhesion, EntityManagerInterface $em): Response
{
    $adhesion->setValider(true);
    $em->flush();

    return $this->redirectToRoute('adhesion_index');
}
}
