<?php

namespace App\Controller;

use App\Entity\ChapitreAchatsCollectif;
use App\Entity\PanierAchatCollectif;
use App\Entity\Etablissement;
use App\Entity\ItemPanierAchatCollectif;
use App\Repository\ChapitreAchatsCollectifRepository;
use App\Repository\DetailAchatsCollectifRepository;
use App\Repository\PanierAchatCollectifRepository;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Service\BudgetValidationService;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/achats-collectifs')]
class AchatsCollectifsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private BudgetValidationService $budgetService, // 👈 Ajoute cette ligne     

    ) {}

   #[Route('/', name: 'app_achats_collectifs_index')]
public function index(
    ChapitreAchatsCollectifRepository $chapitreRepo,
    Security $security,
    EntityManagerInterface $em
): Response {
    $user = $security->getUser();
    $etablissement = $user->getEtablissement();

    // Récupère les chapitres avec leurs budgets
     $chapitres = $chapitreRepo->createQueryBuilder('c')
        ->join('c.chapitreBudget', 'cb')
        ->join('cb.budgets', 'b')
        ->select('c, cb, b')
        ->where('b.idEtablissement = :etab') // 👈 Filtre par établissement
        ->setParameter('etab', $etablissement)
        ->getQuery()
        ->getResult();
       // dd($chapitres);
    // Calcule les totaux achetés par chapitre
    $totauxAchetes = [];
    foreach ($chapitres as $chapitre) {
        $total = $em->createQuery("
            SELECT SUM(i.quantite * dac.prixVente)
            FROM App\Entity\ItemPanierAchatCollectif i
            JOIN i.detailAchatCollectif dac
            JOIN i.panier p
            WHERE dac.chapitreAchatsCollectif = :chapitre
            AND p.etablissement = :etab
        ")
        ->setParameter('chapitre', $chapitre)
        ->setParameter('etab', $etablissement)
        ->getSingleScalarResult();

        $totauxAchetes[$chapitre->getId()] = $total ?: 0;
    }

    return $this->render('achats_collectifs/index.html.twig', [
        'chapitres' => $chapitres,
        'totauxAchetes' => $totauxAchetes,
    ]);
}

    #[Route('/chapitre/{id}', name: 'app_achats_collectifs_chapitre')]
    public function chapitre(
        ChapitreAchatsCollectif $chapitre,
        DetailAchatsCollectifRepository $detailRepo,
        PanierAchatCollectifRepository $panierRepo
    ): Response {
        $articles = $detailRepo->findBy(['chapitreAchatsCollectif' => $chapitre]);
        $user = $this->security->getUser();
        $etablissement = $user->getEtablissement();

        // Récupère le panier
        $panier = $panierRepo->findOneBy([
            'etablissement' => $etablissement,
            'chapitreAchatsCollectif' => $chapitre,
            'etat' => 'en_cours',
        ]);
        return $this->render('achats_collectifs/chapitre.html.twig', [
            'chapitre' => $chapitre,
            'articles' => $articles,
            'panier' => null,
        ]);
    }
    #[Route('/panier', name: 'app_achats_collectifs_panier')]
public function panier(
    Security $security,
    PanierAchatCollectifRepository $panierRepo
): Response {
    $user = $security->getUser();

    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        // Récupère tous les paniers pour l'admin (sans jointures)
        $qb = $panierRepo->createQueryBuilder('p')
            ->join('p.etablissement', 'e') // 👈 1 jointure
            ->join('p.chapitreAchatsCollectif', 'c') // 👈 2 jointures
            ->select('p'); 
        $paniers = $qb->getQuery()->getResult();
    } else {
        // Récupère uniquement le panier de l'utilisateur connecté (sans jointures)
        $etablissement = $user->getEtablissement();
        $qb = $panierRepo->createQueryBuilder('p')
            ->join('p.etablissement', 'e') // 👈 1 jointure
            ->join('p.chapitreAchatsCollectif', 'c') // 👈 2 jointures
            ->where('p.etablissement = :etab')
            ->setParameter('etab', $etablissement)
            ->select('p'); 
        $paniers = $qb->getQuery()->getResult();
    }
    return $this->render('achats_collectifs/panier.html.twig', [
        'paniers' => $paniers,
    ]);
}

    #[Route('/ajouter/{id}', name: 'app_achats_collectifs_ajouter', methods: ['POST'])]
    public function ajouter(
        ChapitreAchatsCollectif $chapitre,
        Request $request,
        DetailAchatsCollectifRepository $detailRepo,
        PanierAchatCollectifRepository $panierRepo
    ): Response {
        $user = $this->security->getUser();
        $etablissement = $user->getEtablissement();

        // Récupère l'article et la quantité
        $articleId = $request->request->get('article');
        $quantite = (int) $request->request->get('quantite');

        if (!$articleId || $quantite <= 0) {
            $this->addFlash('error', 'Veuillez sélectionner un article et une quantité valide.');
            return $this->redirectToRoute('app_achats_collectifs_chapitre', ['id' => $chapitre->getId()]);
        }

        $detail = $detailRepo->find($articleId);

        // Vérifie que l'article appartient au chapitre
        if ($detail->getChapitreAchatsCollectif() !== $chapitre) {
            $this->addFlash('error', 'Article invalide pour ce chapitre.');
            return $this->redirectToRoute('app_achats_collectifs_chapitre', ['id' => $chapitre->getId()]);
        }

        // Récupère le montant total
        $montantTotal = $quantite * $detail->getPrixVente();

        // Vérifie le solde
        $chapitreBudget = $detail->getChapitreAchatsCollectif()->getChapitreBudget();
        if (!$this->budgetService->verifierSolde($etablissement, $chapitreBudget, $montantTotal)) {
            $this->addFlash('error', 'Solde insuffisant dans le budget pour ce chapitre.');
            return $this->redirectToRoute('app_achats_collectifs_chapitre', ['id' => $chapitre->getId()]);
        }

        // Récupère ou crée le panier
        $panier = $panierRepo->findOneBy([
            'etablissement' => $etablissement,
            'chapitreAchatsCollectif' => $chapitre,
            'etat' => 'en_cours',
        ]);

        if (!$panier) {
            $panier = new PanierAchatCollectif();
            $panier->setEtablissement($etablissement);
            $panier->setChapitreAchatsCollectif($chapitre);
            $panier->setAnneeAchats(date('Y'));
            $panier->setEtat('en_cours');
            $panier->setCreatedAt(new \DateTimeImmutable());
            $this->em->persist($panier);
        }

        // Vérifie si l'article est déjà dans le panier
        $itemExistant = null;
        foreach ($panier->getItems() as $item) {
            if ($item->getDetailAchatCollectif() === $detail) {
                $itemExistant = $item;
                break;
            }
        }

        if ($itemExistant) {
            $itemExistant->setQuantite($itemExistant->getQuantite() + $quantite);
        } else {
            $item = new ItemPanierAchatCollectif();
            $item->setPanier($panier);
            $item->setDetailAchatCollectif($detail);
            $item->setQuantite($quantite);
            $panier->addItem($item);
        }

        $this->em->flush();

        $this->addFlash('success', 'Article ajouté au panier.');

        return $this->redirectToRoute('app_achats_collectifs_chapitre', ['id' => $chapitre->getId()]);
    }

    #[Route('/item/{id}/delete', name: 'app_achats_collectifs_item_delete', methods: ['POST'])]
public function deleteItem(
    ItemPanierAchatCollectif $item,
    Security $security,
    EntityManagerInterface $em
): Response {
    $user = $security->getUser();

    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        // L'admin peut supprimer n'importe quel item
    } else {
        // L'utilisateur ne peut supprimer que ses propres items
        $etablissement = $user->getEtablissement();
        if ($item->getPanier()->getEtablissement() !== $etablissement) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer cet item.');
            return $this->redirectToRoute('app_achats_collectifs_panier');
        }
    }

    $em->remove($item);
    $em->flush();

    $this->addFlash('success', 'Item supprimé avec succès.');

    return $this->redirectToRoute('app_achats_collectifs_panier');
}


// #[Route('/imprimer', name: 'app_achats_collectifs_imprimer')]
// public function imprimer(
//     Request $request,
//     Security $security,
//     PanierAchatCollectifRepository $panierRepo,
//     EntityManagerInterface $em
// ): Response {
//     $user = $security->getUser();
//     $etablissement = $user->getEtablissement();

//     // Récupère les chapitres
//     $chapitres = $em->getRepository(ChapitreAchatsCollectif::class)->findAll();

//     // Récupère les établissements (pour l'admin)
//     $etablissements = [];
//     if (in_array('ROLE_ADMIN', $user->getRoles())) {
//         $etablissements = $em->getRepository(Etablissement::class)->findAll();
//     }

//     // Récupère les achats selon les filtres
//     $etabFilter = $request->query->get('etab');
//     $chapitreFilter = $request->query->get('chapitre');

//     $qb = $panierRepo->createQueryBuilder('p')
//         ->join('p.etablissement', 'e')
//         ->join('p.chapitreAchatsCollectif', 'c')
//         ->join('p.items', 'i')
//         ->join('i.detailAchatCollectif', 'dac')
//         ->join('dac.article', 'a')
//         ->select('a.id as article_id, a.nom as article_nom, dac.prixVente as prix_vente, SUM(i.quantite) as total_quantite, SUM(i.quantite * dac.prixVente) as total_montant')
//         ->groupBy('a.id, a.nom, dac.prixVente');

//     if (in_array('ROLE_ADMIN', $user->getRoles())) {
//         if ($etabFilter) {
//             $qb->andWhere('p.etablissement = :etabFilter')
//                 ->setParameter('etabFilter', $etabFilter);
//         }
//     } else {
//         $qb->andWhere('p.etablissement = :etab')
//             ->setParameter('etab', $etablissement);
//     }

//     if ($chapitreFilter) {
//         $qb->andWhere('p.chapitreAchatsCollectif = :chapitreFilter')
//             ->setParameter('chapitreFilter', $chapitreFilter);
//     }

//     $achats = $qb->getQuery()->getResult();

//     return $this->render('achats_collectifs/imprimer.html.twig', [
//         'achats' => $achats,
//         'chapitres' => $chapitres,
//         'etablissements' => $etablissements,
//         'etabFilter' => $etabFilter,
//         'chapitreFilter' => $chapitreFilter,
//     ]);
// }

#[Route('/achats-collectifs/tableau-recapitulatif', name: 'achats_collectifs_tableau_recap')]
public function tableauRecapitulatif(
    DetailAchatsCollectifRepository $achatsRepo,
    EtablissementRepository $etabRepo
): Response {
    // Récupère tous les établissements
    $etablissements = $etabRepo->findAll();

    // Récupère tous les articles distincts
    $articles = $achatsRepo->findDistinctArticles();

    // Récupère les quantités par établissement et article
    $quantites = $achatsRepo->getQuantitiesByEtablissementAndArticle();

    return $this->render('achats_collectifs/tableau_recap.html.twig', [
        'etablissements' => $etablissements,
        'articles' => $articles,
        'quantites' => $quantites,
    ]);
}

#[Route('/imprimer', name: 'app_achats_collectifs_imprimer')]
public function imprimer(
    Request $request,
    PanierAchatCollectifRepository $repo,
    EtablissementRepository $etabRepo,
    ChapitreAchatsCollectifRepository $chapRepo
): Response {
    $etabFilter = $request->query->get('etab');
    $chapitreFilter = $request->query->get('chapitre');

    $achats = $repo->getAchatsCollectifsAvecDetails($etabFilter, $chapitreFilter);

    $etablissements = $etabRepo->findAll();
    $chapitres = $chapRepo->findAll();
//dd($achats);
    return $this->render('achats_collectifs/imprimer_page.html.twig', [
        'achats' => $achats,
        'etablissements' => $etablissements,
        'chapitres' => $chapitres,
        'etabFilter' => $etabFilter,
        'chapitreFilter' => $chapitreFilter,
    ]);
}
#[Route('/achats-collectifs/rapport', name: 'rapports_achats_collectifs')]
public function rapport(
    Request $request,
    PanierAchatCollectifRepository $repo,
    EtablissementRepository $etabRepo,
    ChapitreAchatsCollectifRepository $chapRepo
): Response {
    $user = $this->getUser();
    $etabFilter = $request->query->get('etab');
    $chapitreFilter = $request->query->get('chapitre');

    // ✅ Si utilisateur normal, forcer le filtre sur son établissement
    if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
        $etabFilter = $user->getEtablissement()->getId();
    } else {
        $etabFilter = $etabFilter ?: null;
    }

    $chapitreFilter = $chapitreFilter ?: null;

    $achats = $repo->getAchatsCollectifsAvecDetails($etabFilter, $chapitreFilter);

    $etablissements = $etabRepo->findAll();
    $chapitres = $chapRepo->findAll();

    return $this->render('rapports/achats_collectifs.html.twig', [
        'achats' => $achats,
        'etablissements' => $etablissements,
        'chapitres' => $chapitres,
        'etabFilter' => $etabFilter,
        'chapitreFilter' => $chapitreFilter,
    ]);
}

#[Route('/resume-achats/imprimer', name: 'app_resume_achats_imprimer')]
public function imprimerResumeAchats(
    PanierAchatCollectifRepository $repo,
    EtablissementRepository $etabRepo
): Response {
    $etablissements = $etabRepo->findAll();

    $achats = $repo->getAchatsParEtablissement();
    $quantites = [];
    $articles = [];

    foreach ($achats as $achat) {
        $etabId = $achat['etab_id'];
        $artId = $achat['article_id'];
        $quantite = $achat['quantite'];

        if (!isset($quantites[$etabId])) {
            $quantites[$etabId] = [];
        }

        $quantites[$etabId][$artId] = $quantite;

        if (!in_array($achat['article_nom'], $articles)) {
            $articles[] = ['id' => $artId, 'nom' => $achat['article_nom']];
        }
    }

    return $this->render('rapports/resume_achats_imprimer.html.twig', [
        'etablissements' => $etablissements,
        'articles' => $articles,
        'quantites' => $quantites,
    ]);
}

#[Route('/rapport-achat-collectif/{chapitre}/{etablissement}', name: 'app_rapport_achat_collectif_show')]
public function rapportDistribution(
    int $chapitre,
    int $etablissement,
    ChapitreAchatsCollectifRepository $chapRepo,
    EtablissementRepository $etabRepo,
    EntityManagerInterface $em
): Response {
    $chapitre = $chapRepo->find($chapitre);
    $etablissement = $etabRepo->find($etablissement);

    // Récupérer les articles commandés pour ce chapitre et établissement
    $achats = $em->createQuery("
        SELECT a.nom as article_nom, SUM(ipac.quantite) as quantite_demandee
        FROM App\Entity\ItemPanierAchatCollectif ipac
        JOIN ipac.panier p
        JOIN ipac.detailAchatCollectif dac
        JOIN dac.article a
        WHERE p.chapitreAchatsCollectif = :chapitre
        AND p.etablissement = :etab
        GROUP BY a.id
    ")
    ->setParameter('chapitre', $chapitre)
    ->setParameter('etab', $etablissement)
    ->getResult();

    return $this->render('rapports/rapport_distribution.html.twig', [
        'achats' => $achats,
        'chapitre' => $chapitre,
        'etablissement' => $etablissement,
    ]);
}
}
