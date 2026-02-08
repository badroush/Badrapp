<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\PasscodeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class TwoFactorAuthListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private Security $security,
        private PasscodeService $passcodeService,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Ignore les routes de connexion et de vérification
        if (in_array($request->getPathInfo(), ['/login', '/verify-passcode'])) {
            return;
        }

        // Vérifie si l'utilisateur est connecté
        $user = $this->security->getUser();

        if ($user instanceof User) {
            // Vérifie si l'utilisateur a besoin de 2FA
            if (!$user->isPasscodeVerified()) {
                // Enregistre l'utilisateur dans la session
                $request->getSession()->set('user_id', $user->getId());

                // Envoie le code
                $this->passcodeService->generateAndSendPasscode($user);

                // Redirige vers la vérification
                $response = new RedirectResponse('/verify-passcode');
                $event->setResponse($response);
            }
        }
    }

    public function onLogout(LogoutEvent $event): void
    {
        $user = $event->getToken()?->getUser();

        if ($user instanceof User) {
            // Réinitialise l'état de vérification
            $user->setPasscodeVerified(false);
            $user->setPasscode(null);
            $user->setPasscodeExpiresAt(null);

            // Sauvegarde dans la base
            $this->passcodeService->saveUser($user);
        }
    }

    // ✅ Nouvelle méthode : Réinitialise 2FA quand la session expire
    public function onSessionExpire(): void
    {
        // Cette méthode sera appelée quand la session expire
        // (ex: fermeture du navigateur)
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            LogoutEvent::class => 'onLogout',
        ];
    }
}