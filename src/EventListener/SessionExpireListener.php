<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\PasscodeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;

class SessionExpireListener implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private PasscodeService $passcodeService,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Ignore les routes de connexion
        if ($request->getPathInfo() === '/login') {
            return;
        }

        // Vérifie si l'utilisateur est connecté
        $user = $this->security->getUser();

        if ($user instanceof User) {
            // Si l'utilisateur est connecté mais n'a pas de session active
            // (ex: fermeture du navigateur), réinitialise 2FA
            if (!$request->hasPreviousSession()) {
                $user->setPasscodeVerified(false);
                $user->setPasscode(null);
                $user->setPasscodeExpiresAt(null);
                $this->passcodeService->saveUser($user);
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}