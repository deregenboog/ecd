<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Custom authenticator to be used during development, without the need to set
 * up a LDAP-server. A user can login by providing its username twice (both as
 * username, and as password) in the login form.
 *
 * NOTE: NEVER USE THIS AUTHENTICATOR IN PRODUCTION!
 */
class DevelopmentAuthenticator extends AbstractAuthenticator
    implements AuthenticationEntryPointInterface
{
    private string $env;
    private RouterInterface $router;

    public function __construct(string $env, RouterInterface $router)
    {
        $this->env = $env;
        $this->router = $router;
    }

    public function supports(Request $request): bool
    {
        // Only supported in 'dev' or 'test' environment.
        if (!in_array($this->env, ['dev', 'test'])) {
            return false;
        }

        // Supports only on private network ranges. IE not in a production environment.
        if ($this->isPublicIP($request->server->get('SERVER_ADDR'))) {
            return false;
        }

        return $request->request->has('_username');
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');
        if (null === $username || null === $password) {
            // The username was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No username or password provided');
        }

        return new Passport(new UserBadge($username), new CustomCredentials(
            // If this function returns anything else than `true`, the credentials
            // are marked as invalid.
            // The $credentials parameter is equal to the next argument of this class
            function (string $credentials, UserInterface $user): bool {
                return $user->getUserIdentifier() === $credentials;
            },

            // The custom credentials
            $password
        ));
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->router->generate('login'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return new RedirectResponse($this->router->generate('home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        /**
         * when returning null, the request continues, thus the next authenticator in the chain_provider will be called.
         * We want this to happen to fall back to other authenticators.
         */
        return null;
        //return new RedirectResponse($this->router->generate('logout'));
    }

    public function isPublicIP($ip=NULL) : bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === $ip ? true : false;
    }
}
