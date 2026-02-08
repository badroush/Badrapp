<?php

namespace App\Controller;

use App\Entity\RessourceAffectation;
use App\Form\RessourceAffectationType;
use App\Repository\RessourceAffectationRepository;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/ressource-affectation')]
class RessourceAffectationController extends AbstractController
{
    #[Route('/', name: 'app_ressource_affectation_index', methods: ['GET'])]
    public function index(RessourceAffectationRepository $ressourceAffectationRepository, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }
        // Récupère les ressources selon le rôle
        $affectations = $ressourceAffectationRepository->findForUser($user);
        //$affectations = $ressourceAffectationRepository->findAll();
        $totalParRessource = [];
        foreach ($affectations as $affectation) {
            $ressourceId = $affectation->getIdRessource()->getId();
            $totalParRessource[$ressourceId] = ($totalParRessource[$ressourceId] ?? 0) + $affectation->getMontant();
        }
        return $this->render('ressource_affectation/index.html.twig', [
            'ressource_affectations' => $affectations,
            'total_par_ressource' => $totalParRessource,
        ]);
    }
 #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_ressource_affectation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ressourceAffectation = new RessourceAffectation();
        $form = $this->createForm(RessourceAffectationType::class, $ressourceAffectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ressourceAffectation);
            $entityManager->flush();
            return $this->redirectToRoute('app_ressource_affectation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ressource_affectation/new.html.twig', [
            'ressource_affectation' => $ressourceAffectation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ressource_affectation_show', methods: ['GET'])]
    public function show(RessourceAffectation $ressourceAffectation): Response
    {
        return $this->render('ressource_affectation/show.html.twig', [
            'ressource_affectation' => $ressourceAffectation,
        ]);
    }
    //#[IsGranted('ROLE_ADMIN')] // ou ROLE_SOUS_ADMIN selon ton besoin
    #[Route('/{id}/edit', name: 'app_ressource_affectation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RessourceAffectation $ressourceAffectation, RessourceAffectationRepository $ressourceAffectationRepository, Security $security, EntityManagerInterface $entityManager): Response
    {
       // 🔒 Sécurité : seul ADMIN et SUPER_ADMIN peuvent modifier
$user = $security->getUser();

// Vérifier si l'utilisateur a les rôles requis
if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
    throw $this->createAccessDeniedException('ليس لديك صلاحية لتحديث هذا المبلغ.');
}
        $form = $this->createForm(RessourceAffectationType::class, $ressourceAffectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'تم تحديث المبلغ بنجاح.');
            //$ressourceAffectationRepository->save($ressourceAffectation, true);

            return $this->redirectToRoute('app_ressource_affectation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ressource_affectation/edit.html.twig', [
            'ressource_affectation' => $ressourceAffectation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ressource_affectation_delete', methods: ['POST'])]
    public function delete(Request $request, RessourceAffectation $ressourceAffectation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Vérifier que l'utilisateur a le droit de supprimer ce matériel
        if ($user->isSousAdmin() && $ressourceAffectation->getIdEtablissement() !== $user->getEtablissement()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce matériel.');
        }

        if ($this->isCsrfTokenValid('delete' . $ressourceAffectation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ressourceAffectation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ressource_affectation_index', [], Response::HTTP_SEE_OTHER);
    }
}
