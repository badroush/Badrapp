<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
<<<<<<< HEAD
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\PasscodeFormType;
use App\Service\PasscodeService;
use Doctrine\ORM\EntityManagerInterface;

class LoginFormAuthenticatorController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/verify-passcode', name: 'app_verify_passcode', methods: ['GET', 'POST'])]
    public function verifyPasscode(
        Request $request,
        EntityManagerInterface $em,
        PasscodeService $passcodeService
    ): Response {
        // Récupère l'utilisateur depuis la session
        $session = $request->getSession();
        $userId = $session->get('user_id');

        $user = $em->find(User::class, $userId);
dd("LFA = ".$user);
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(PasscodeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passcode = $form->get('passcode')->getData();

            if ($passcodeService->verifyPasscode($user, $passcode)) {
                // Connexion réussie
                $session->set('is_passcode_verified', true);
                return $this->redirectToRoute('app_dashboard'); // Remplace par ta route
            }

            $this->addFlash('error', 'Code invalide ou expiré.');
        }

        return $this->render('security/verify_passcode.html.twig', [
            'form' => $form->createView(),
        ]);
    }
=======

class LoginFormAuthenticatorController extends AbstractController
{
 #[Route('/login', name: 'app_login')]
public function login(AuthenticationUtils $authenticationUtils): Response
{
    return $this->render('security/login.html.twig', [
        'last_username' => $authenticationUtils->getLastUsername(),
        'error' => $authenticationUtils->getLastAuthenticationError(),
    ]);
}
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
<<<<<<< HEAD
}
=======

    public function getCredentials(Request $request)
{
    $credentials = [
        'email' => $request->request->get('_username'),
        'password' => $request->request->get('_password'),
        'csrf_token' => $request->request->get('_csrf_token'),
    ];
}

public function validateCredentials(PasswordAuthenticatedUserInterface $user, UserCheckerInterface $userChecker, array $credentials, Request $request): bool
{
    // ...
    if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $credentials['csrf_token']))) {
        throw new InvalidCsrfTokenException();
    }
}
}
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
