<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedException) {
            $request = $event->getRequest();

            // Vérifier si la requête est AJAX
            if ($request->isXmlHttpRequest()) {
                // Répondre avec JSON pour SweetAlert
                $response = new \Symfony\Component\HttpFoundation\JsonResponse([
                    'success' => false,
                    'message' => 'عفوا. لا يمكنك الوصول إلى هذه الصفحة.',
                ], 403);
                $event->setResponse($response);
            } else {
                // Rediriger vers la page précédente
                $referer = $request->headers->get('referer');
                $route = $this->urlGenerator->generate('app_home'); // Fallback

                if ($referer && $referer !== $request->getUri()) {
                    $route = $referer;
                }

                $response = new RedirectResponse($route);

                // Ajouter un message flash pour afficher avec SweetAlert
                $session = $this->requestStack->getSession();
                $session->getFlashBag()->add('error', 'عفوا. لا يمكنك الوصول إلى هذه الصفحة.');

                $event->setResponse($response);
            }
        }
    }
}
