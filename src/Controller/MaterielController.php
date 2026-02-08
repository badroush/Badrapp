<?php

namespace App\Controller;

use App\Entity\Materiel;
<<<<<<< HEAD
use App\Entity\User;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use App\Repository\LibelleMaterielRepository; // ✅ Ajout
=======
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
<<<<<<< HEAD
use App\Repository\EtablissementRepository;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\ActionControlService;
=======

>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

#[Route('/materiel')]
#[IsGranted('ROLE_USER')]
class MaterielController extends AbstractController
{
<<<<<<< HEAD
    public function __construct(
        private MaterielRepository $materielRepo,
         private ActionControlService $actionControlService
    ) {
    }

=======

    public function __construct(
        private MaterielRepository $materielRepo, // ✅ Injection du repository
    ) {
    }
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    #[Route('/', name: 'materiel_index')]
    public function index(MaterielRepository $materielRepo): Response
    {
        $user = $this->getUser();

<<<<<<< HEAD
        // Vérifie si l'utilisateur est ADMIN ou SUPER_ADMIN
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN')) {
            // Affiche tous les matériels
            $matériels = $materielRepo->findAll();
        } else {
            // Affiche seulement les matériels de l'établissement de l'utilisateur
            if ($user->getEtablissement()) {
                $matériels = $materielRepo->findByEtablissement($user->getEtablissement());
            } else {
                // Si l'utilisateur n'a pas d'établissement, affiche une liste vide
                $matériels = [];
            }
=======
        if ($user->isSousAdmin() && $user->getEtablissement()) {
            // Sous-admin : voir uniquement les matériels de son établissement
            $matériels = $materielRepo->findByEtablissement($user->getEtablissement());
        } else {
            // Admin principal : voir tous les matériels
            $matériels = $materielRepo->findAll();
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        }

        return $this->render('materiel/index.html.twig', [
            'matériels' => $matériels,
<<<<<<< HEAD
            'action_control' => $this->actionControlService,
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        ]);
    }

    #[Route('/new', name: 'materiel_new', methods: ['GET', 'POST'])]
<<<<<<< HEAD
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $materiel = new Materiel();
    $form = $this->createForm(MaterielType::class, $materiel);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if ($form->isValid()) {            
            $user = $this->getUser();
            if (!($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN'))) {
                if ($user->getEtablissement()) {
                    $materiel->setEtablissement($user->getEtablissement());
                }
            }

            $entityManager->persist($materiel);
            $entityManager->flush();

            // ✅ Message de succès
            $this->addFlash('success', 'تمت إضافة المادة بنجاح!');
            return $this->redirectToRoute('materiel_index', [], Response::HTTP_SEE_OTHER);
        } else {
            // ✅ Message d'erreur
            $this->addFlash('error', 'الرجاء التحقق من صحة البيانات المدخلة.');
            
            // Log des erreurs de validation
            foreach ($form->getErrors(true) as $error) {
                error_log("Erreur: " . $error->getMessage());
            }
        }
    }

    return $this->render('materiel/new.html.twig', [
        'materiel' => $materiel,
        'form' => $form,
    ]);
}
    #[Route('/{id}/edit', name: 'materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
=======
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Lier automatiquement le matériel à l'établissement de l'utilisateur connecté
            $user = $this->getUser();
            if ($user->isSousAdmin() && $user->getEtablissement()) {
                $materiel->setEtablissement($user->getEtablissement());
            }
            $materiel->setNom(ucwords(strtoupper(trim($materiel->getNom()))));
            $materiel->setEmplacement(ucwords(strtoupper(trim($materiel->getEmplacement()))));
            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        $user = $this->getUser();

        // Vérifier que l'utilisateur a le droit de modifier ce matériel
        if ($user->isSousAdmin() && $materiel->getEtablissement() !== $user->getEtablissement()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce matériel.');
        }

        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'materiel_delete', methods: ['POST'])]
    public function delete(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
<<<<<<< HEAD
            $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        $user = $this->getUser();

        // Vérifier que l'utilisateur a le droit de supprimer ce matériel
        if ($user->isSousAdmin() && $materiel->getEtablissement() !== $user->getEtablissement()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce matériel.');
        }

        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($materiel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('materiel_index', [], Response::HTTP_SEE_OTHER);
    }

<<<<<<< HEAD
    // ✅ Mise à jour de la recherche AJAX pour utiliser libelleMateriel
    #[Route('/api/materiel/search', name: 'materiel_search', methods: ['GET'])]
public function search(
    Request $request, 
    LibelleMaterielRepository $libelleRepo,
    EntityManagerInterface $em
): JsonResponse {
    $q = $request->query->get('q');
    $field = $request->query->get('field'); // 'nom' ou 'emplacement'

=======
#[Route('/api/materiel/search', name: 'materiel_search', methods: ['GET'])]
public function search(Request $request): JsonResponse
{
    $q = $request->query->get('q');
    $field = $request->query->get('field'); // 'nom' ou 'emplacement'

    // 🔧 Ajouter des logs
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    error_log("Requête de recherche: q=$q, field=$field");

    $results = [];

    if ($field === 'nom' && $q) {
<<<<<<< HEAD
        $results = $libelleRepo->findByName($q);
=======
        $results = $this->materielRepo->findSimilarNames($q);
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    } elseif ($field === 'emplacement' && $q) {
        $results = $this->materielRepo->findSimilarEmplacements($q);
    }

<<<<<<< HEAD
=======
    // 🔧 Vérifier les résultats
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    error_log("Résultats trouvés: " . count($results));

    $data = [];
    if (count($results) === 0) {
<<<<<<< HEAD
        if ($field === 'nom') {
            $data[] = ['id' => $q, 'text' => $q];
        } elseif ($field === 'emplacement') {
            // ✅ Ne pas créer de matériel ici, juste retourner la suggestion
            $data[] = ['id' => $q, 'text' => $q];
        }
        return $this->json($data);
    }

    foreach ($results as $item) {
        if ($field === 'nom') {
            $data[] = [
                'id' => $item->getid(), 
                'text' => $item->getNom(),
                'reference' => $item->getReference()
            ];
=======
        $data[] = ['id' => $q, 'text' => $q];
        return $this->json($data);
    }
    foreach ($results as $item) {
        if ($field === 'nom') {
            $data[] = ['id' => $item['nom'], 'text' => $item['nom']];
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        } elseif ($field === 'emplacement') {
            $data[] = ['id' => $item['emplacement'], 'text' => $item['emplacement']];
        }
    }
<<<<<<< HEAD
    return $this->json($data);
}

    #[Route('/inventaire/filtrer', name: 'materiel_filtrer')]
    public function filtrer(
        Request $request,
        MaterielRepository $materielRepo,
        EtablissementRepository $etablissementRepo,
        Security $security
    ): Response {
        $user = $security->getUser();

        // Récupère les filtres
        $dateDebut = $request->query->get('date_debut');
        $dateFin = $request->query->get('date_fin');
        $etablissement = $request->query->get('etablissement');
        $emplacement = $request->query->get('emplacement');
        $etat = $request->query->get('etat');

        // ✅ Seuls ADMIN/SUPER_ADMIN voient tous les établissements
        if ($security->isGranted('ROLE_ADMIN') || $security->isGranted('ROLE_SUPER_ADMIN')) {
            $etablissements = $etablissementRepo->findAll();
        } else {
            // ✅ Sinon, seul l'établissement de l'utilisateur est disponible
            $etablissements = [$user->getEtablissement()];
        }

        $emplacements = $materielRepo->findDistinctEmplacements();
        $etats = $materielRepo->findDistinctEtats();

        // Applique les filtres
        $materiels = $materielRepo->findByFilters(
            $dateDebut ? new \DateTime($dateDebut) : null,
            $dateFin ? new \DateTime($dateFin) : null,
            $etablissement,
            $emplacement,
            $etat,
            $user,
            $security
        );

        return $this->render('materiel/filtrer.html.twig', [
            'materiels' => $materiels,
            'etablissements' => $etablissements,
            'emplacements' => $emplacements,
            'etats' => $etats,
            'filters' => [
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'etablissement' => $etablissement,
                'emplacement' => $emplacement,
                'etat' => $etat,
            ],
        ]);
    }

    #[Route('/materiel/imprimer', name: 'materiel_imprimer')]
    public function imprimer(
        Request $request,
        MaterielRepository $materielRepo,
        EtablissementRepository $etablissementRepo,
        Security $security
    ): Response {
        $user = $security->getUser();
        $region = null;
        $etab= null;
        // Récupérer les filtres
        $dateDebut = $request->query->get('date_debut');
        $dateFin = $request->query->get('date_fin');
        $etablissement = $request->query->get('etablissement');
        $emplacement = $request->query->get('emplacement');
        $etat = $request->query->get('etat');

        // Appliquer les filtres
        $qb = $materielRepo->createQueryBuilder('m')
            ->leftJoin('m.etablissement', 'e')
            ->leftJoin('m.libelleMateriel', 'lm'); // ✅ Jointure avec libelleMateriel

        // ✅ Filtrer par établissement pour les utilisateurs normaux
        if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            $qb->andWhere('e.id = :etab_user')
                ->setParameter('etab_user', $user->getEtablissement()->getId());
        } else {
            // ✅ Pour les admins : filtre optionnel
            if ($etablissement) {
                $qb->andWhere('e.id = :etab')
                    ->setParameter('etab', $etablissement);
                $etab= $etablissementRepo->find($etablissement)->getNom();
            }
        }

        if ($dateDebut) {
            $qb->andWhere('m.dateAcquisition >= :dateDebut')
                ->setParameter('dateDebut', new \DateTime($dateDebut));
        }
        if ($dateFin) {
            $qb->andWhere('m.dateAcquisition <= :dateFin')
                ->setParameter('dateFin', new \DateTime($dateFin));
        }
        if ($emplacement) {
            $qb->andWhere('m.emplacement = :emplacement')
                ->setParameter('emplacement', $emplacement);
                $region= $emplacement;
        }
        if ($etat) {
            $qb->andWhere('m.etat = :etat')
                ->setParameter('etat', $etat);
        }

        $materiels = $qb->getQuery()->getResult();

        return $this->render('materiel/imprimer_inventaire.html.twig', [
            'materiels' => $materiels,
            'region'=> $region,
            'etab'=> $etab,
        ]);
    }
}
=======

    return $this->json($data);
}
}
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
