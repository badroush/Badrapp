<?php
// src/Controller/DemandeMaintenanceController.php

namespace App\Controller;

use App\Entity\DemandeMaintenance;
use App\Entity\ReponseTechnique;
use App\Form\DemandeMaintenanceType;
use App\Form\ReponseTechniqueType;
use App\Repository\DemandeMaintenanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\HeartbeatStatus;
use App\Service\MailService;


#[Route('/demande/maintenance')]
class DemandeMaintenanceController extends AbstractController
{

    #[Route('/', name: 'app_demande_maintenance_index', methods: ['GET'])]
public function index(
    DemandeMaintenanceRepository $demandeMaintenanceRepository,
    Security $security
): Response {
    $user = $security->getUser();
    $isAdmin = in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_SUPER_ADMIN', $user->getRoles()) || in_array('ROLE_TECH', $user->getRoles());
    if ($isAdmin) {
        // Admin : voir toutes les demandes
        $demandes = $demandeMaintenanceRepository->findAll();
    } else {
        // Utilisateur normal : voir seulement les demandes de son établissement
        $demandes = $demandeMaintenanceRepository->findBy(['etablissement' => $user->getEtablissement()]);
    }
    return $this->render('demande_maintenance/index.html.twig', [
        'demande_maintenances' => $demandes,
    ]);
}

    #[Route('/new', name: 'app_demande_maintenance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {$user = $this->getUser();
        $demandeMaintenance = new DemandeMaintenance();
        $form = $this->createForm(DemandeMaintenanceType::class, $demandeMaintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$demandeMaintenance->getEtablissement()) {
            $demandeMaintenance->setEtablissement($user->getEtablissement());
        }
        if (!$demandeMaintenance->getResponsableDemande()) {
            $demandeMaintenance->setResponsableDemande($user);
        }
            $entityManager->persist($demandeMaintenance);
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_maintenance/new.html.twig', [
            'demande_maintenance' => $demandeMaintenance,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_demande_maintenance_show', methods: ['GET'])]
    public function show(DemandeMaintenance $demandeMaintenance): Response
    {
        return $this->render('demande_maintenance/show.html.twig', [
            'demande_maintenance' => $demandeMaintenance,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_demande_maintenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeMaintenance $demandeMaintenance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeMaintenanceType::class, $demandeMaintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_maintenance/edit.html.twig', [
            'demande_maintenance' => $demandeMaintenance,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_demande_maintenance_delete', methods: ['POST'])]
    public function delete(Request $request, DemandeMaintenance $demandeMaintenance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeMaintenance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demandeMaintenance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_maintenance_index', [], Response::HTTP_SEE_OTHER);
    }

#[Route('/{id<\d+>}/reponse/new', name: 'app_reponse_technique_new_for_demande', methods: ['GET', 'POST'])]
public function newReponseForDemande(
    Request $request, 
    DemandeMaintenance $demande, 
    EntityManagerInterface $entityManager,
    MailService $mailService // Injection du service
): Response {
    $user = $this->getUser();
    // Vérifier que l'utilisateur est un technicien
    if (!in_array('ROLE_TECH', $user->getRoles())) {
        throw $this->createAccessDeniedException('Accès refusé.');
    }
    $reponse = new ReponseTechnique();
    $reponse->setDemande($demande);
    $reponse->setTechnicien($user);
    $form = $this->createForm(ReponseTechniqueType::class, $reponse);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($reponse);
        $entityManager->flush();
        // Changer le statut de la demande
        $demande->setStatut('en_cours');
        $entityManager->flush();
        // Envoyer l'email de notification
        $mailService->sendReponseNotification(
            $demande->getResponsableDemande(), // Utilisateur qui a fait la demande
            $demande,
            $reponse
        );
        return $this->redirectToRoute('app_demande_maintenance_index');
    }
    return $this->render('reponse_technique/new_from_demande.html.twig', [
        'demande' => $demande,
        'form' => $form->createView(),
    ]);
}
#[Route('/{id<\d+>}/reponses', name: 'app_reponse_technique_list_by_demande', methods: ['GET'])]
public function listReponsesByDemande(DemandeMaintenance $demande): Response
{
    return $this->render('reponse_technique/list_by_demande.html.twig', [
        'demande' => $demande,
        'reponses' => $demande->getReponsesTechniques(),
    ]);
}


}