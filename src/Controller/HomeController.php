<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
public function index(): Response
{
    return $this->render('home/index.html.twig');
}
<<<<<<< HEAD
#[Route('/access-denied', name: 'access_denied')]
public function accessDenied(): Response
{
    return $this->render('security/access_denied.html.twig');
}
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
}
