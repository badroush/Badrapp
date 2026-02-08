<?php

namespace App\Controller;

use App\Repository\MouvementStockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    #[Route('/stock', name: 'stock_index')]
    public function index(MouvementStockRepository $mouvementRepo): Response
    {
<<<<<<< HEAD
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        $stockSummary = $mouvementRepo->findStockSummary();
        return $this->render('stock/index.html.twig', [
            'stockSummary' => $stockSummary,
        ]);
    }

    #[Route('/mouvements', name: 'mouvement_index')]
    public function mouvements(MouvementStockRepository $mouvementRepo): Response
    {
<<<<<<< HEAD
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // <-- Ici
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        // Charger TOUS les mouvements (ou paginer plus tard si besoin)
        $mouvements = $mouvementRepo->findBy([], ['dateMouvement' => 'DESC']);
        return $this->render('stock/mouvements.html.twig', [
            'mouvements' => $mouvements,
        ]);
    }
}
