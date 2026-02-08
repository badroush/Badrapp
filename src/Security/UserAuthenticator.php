<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
<<<<<<< HEAD
use App\Entity\User;
use App\Service\PasscodeService;
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

<<<<<<< HEAD
    public function __construct(private UrlGeneratorInterface $urlGenerator,private PasscodeService $passcodeService)
=======
    public function __construct(private UrlGeneratorInterface $urlGenerator)
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->getPayload()->getString('email');
<<<<<<< HEAD
dd($email);
=======

>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
<<<<<<< HEAD
        $user = $token->getUser();
    // Vérifie si l'utilisateur a besoin de 2FA
    if (!$user->isPasscodeVerified()) {
        // Génère et envoie le code
        $this->passcodeService->generateAndSendPasscode($user);

        // Redirige vers la vérification
        return new RedirectResponse($this->urlGenerator->generate('app_verify_passcode'));
    }

    // Sinon, redirige normalement
    if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        return new RedirectResponse($targetPath);
    }

    return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
=======
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
