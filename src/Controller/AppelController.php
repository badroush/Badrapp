<?php

namespace App\Controller;

use App\Entity\Appel;
use App\Form\AppelType;
use App\Form\AppelFilterType;
use App\Repository\AppelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Route('/appel')]
final class AppelController extends AbstractController
{
    #[Route('/appel', name: 'app_appel_index', methods: ['GET'])]
public function index(
    AppelRepository $appelRepository,
    Request $request
): Response {
    $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
    $form = $this->createForm(AppelFilterType::class);
    $form->handleRequest($request);

    $filters = [];

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        if ($data['dateDebut']) {
            $filters['dateAppel']['gte'] = $data['dateDebut'];
        }
        if ($data['dateFin']) {
            $filters['dateAppel']['lte'] = $data['dateFin'];
        }
        if ($data['contact']) {
            $filters['contactEmetteur'] = $data['contact']->getId();
        }
        if ($data['type']) {
            $filters['type'] = $data['type'];
        }
    }

    $appels = $appelRepository->findByFilters($filters);

    return $this->render('appel/index.html.twig', [
        'appels' => $appels,
        'filterForm' => $form->createView(),
    ]);
}
    #[Route('/new', name: 'app_appel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $appel = new Appel();
        $form = $this->createForm(AppelType::class, $appel);
        $form->handleRequest($request);
        $appel->setHeureAppel(new \DateTime());
        $appel->setDateAppel(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appel);
            $entityManager->flush();

            return $this->redirectToRoute('app_appel_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('appel/new.html.twig', [
            'appel' => $appel,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_appel_show', methods: ['GET'])]
    public function show(Appel $appel): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        return $this->render('appel/show.html.twig', [
            'appel' => $appel,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_appel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appel $appel, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        $form = $this->createForm(AppelType::class, $appel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_appel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('appel/edit.html.twig', [
            'appel' => $appel,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_appel_delete', methods: ['POST'])]
    public function delete(Request $request, Appel $appel, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
        if ($this->isCsrfTokenValid('delete'.$appel->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($appel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appel_index', [], Response::HTTP_SEE_OTHER);
    }
#[Route('/appel/export', name: 'app_appel_export', methods: ['GET'])]
public function export(
    AppelRepository $appelRepository,
    Request $request
): Response {
    // Sécuriser l'accès à l'export (seulement pour ROLE_ADMIN)
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $filters = [];

    // Récupérer les filtres depuis les paramètres GET
    $dateDebut = $request->query->get('dateDebut');
    $dateFin = $request->query->get('dateFin');
    $contact = $request->query->get('contact');
    $type = $request->query->get('type');

    if ($dateDebut) {
        $filters['dateAppel']['gte'] = new \DateTime($dateDebut);
    }
    if ($dateFin) {
        $filters['dateAppel']['lte'] = new \DateTime($dateFin);
    }
    if ($contact) {
        $filters['contactEmetteur'] = $contact;
    }
    if ($type) {
        $filters['type'] = $type;
    }

    $appels = $appelRepository->findByFilters($filters);

    // Générer le CSV
    $response = new StreamedResponse(function () use ($appels) {
        $handle = fopen('php://output', 'w');

        // Entêtes du CSV
        fputcsv($handle, ['الجهة المُرسلة', 'الجهة المستقبلة', 'نوع المكالمة', 'التاريخ', 'الوقت']);

        foreach ($appels as $appel) {
            fputcsv($handle, [
                $appel->getContactEmetteur()->getNom(),
                $appel->getContactRecepteur()->getNom(),
                $appel->getType(),
                $appel->getDateAppel()->format('Y-m-d'),
                $appel->getHeureAppel()->format('H:i'),
            ]);
        }

        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="appels.csv"');

    return $response;
}
}
