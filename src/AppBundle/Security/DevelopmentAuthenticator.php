<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class DevelopmentAuthenticator extends AbstractGuardAuthenticator
{
    public function supports(Request $request)
    {
        //Supports only on private network ranges. IE not in a production environment.
        $serverIp = $request->server->get('SERVER_ADDR');
        if($this->is_public_ip($serverIp)) return false;

        return $request->request->has('_username');
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function getCredentials(Request $request)
    {
        return [
            'username' => $request->request->get('_username'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse('/');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse('/logout');
    }

    public function is_public_ip($ip=NULL) : bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === $ip ? TRUE : FALSE;
    }
}
