<?php

namespace App\Controller;

use App\Entity\MouvementStock;
use App\Form\MouvementStockType;
use App\Repository\MouvementStockRepository;
use App\Service\StockService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
<<<<<<< HEAD
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN'), IsGranted('ROLE_MAG')]
=======

>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
class MouvementStockController extends AbstractController
{
    #[Route('/mouvement/ajouter', name: 'mouvement_ajouter')]
    public function ajouter(
        Request $request,
        EntityManagerInterface $entityManager,
        StockService $stockService,
        MouvementStockRepository $mouvementRepo
    ): Response {
        $mouvement = new MouvementStock();
        $form = $this->createForm(MouvementStockType::class, $mouvement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Applique le mouvement ET met à jour le stock
                $stockService->appliquerMouvement($mouvement);

                // Sauvegarde le mouvement lui-même
                $entityManager->persist($mouvement);
                $entityManager->flush();
                return $this->redirectToRoute('mouvement_ajouter', ['added' => 'mouvement']);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }
        $derniersMouvements = $mouvementRepo->findBy([], ['dateMouvement' => 'DESC'], 10);
$stockSummary = $mouvementRepo->findStockSummary();
        // Récupérer les 10 derniers mouvements
        $derniersMouvements = $mouvementRepo->findBy([], ['dateMouvement' => 'DESC'], 10);

        return $this->render('mouvement_stock/ajouter.html.twig', [
    'form' => $form->createView(),
    'mouvements' => $derniersMouvements,
    'stockSummary' => $stockSummary, // ← ajouté
]);
    }
    #[Route('/mouvement/{id}/delete', name: 'mouvement_delete', methods: ['DELETE'])]
public function delete(
    MouvementStock $mouvement,
    EntityManagerInterface $entityManager
): Response {
    $article = $mouvement->getArticle();
    $quantite = $mouvement->getQuantite();
    $type = $mouvement->getType();

    // Réajuster le stock
    if ($type === 'entree') {
        $article->setStock($article->getStock() - $quantite);
    } elseif ($type === 'sortie') {
        $article->setStock($article->getStock() + $quantite);
    }

    $entityManager->remove($mouvement);
    $entityManager->flush();

    return $this->json(['success' => true]);
}
#[Route('/mouvement/create-ajax', name: 'mouvement_create_ajax', methods: ['POST'])]
public function createAjax(
    Request $request,
    EntityManagerInterface $entityManager,
    StockService $stockService
): Response {
    $mouvement = new MouvementStock();
    $form = $this->createForm(MouvementStockType::class, $mouvement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            $stockService->appliquerMouvement($mouvement);
            $entityManager->persist($mouvement);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'mouvement' => [
                    'id' => $mouvement->getId(),
                    'date' => $mouvement->getDateMouvement()->format('Y-m-d H:i'),
                    'article' => $mouvement->getArticle()->getNom(),
                    'type' => $mouvement->getType(),
                    'quantite' => $mouvement->getQuantite(),
                    'fournisseur' => $mouvement->getFournisseur()?->getNom(),
                    'beneficiaire' => $mouvement->getBeneficiaire()?->getNom()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    return $this->json(['success' => false, 'message' => 'Formulaire invalide'], 400);
}
}
