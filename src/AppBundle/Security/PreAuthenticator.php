<?php

namespace AppBundle\Security;

use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class PreAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @var RolesMapper
     */
    private $rolesMapper;

    public function __construct(RolesMapper $rolesMapper)
    {
        $this->rolesMapper = $rolesMapper;
    }

    public function createToken(Request $request, $providerKey)
    {
        $username = @$_SESSION['Auth']['Medewerker']['username'];

        if (!$username) {
            return null;
        }

        return new PreAuthenticatedToken(
            'anon.',
            $username,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof EntityUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of EntityUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $username = $token->getCredentials();
        if (!$username) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            throw new CustomUserMessageAuthenticationException(
                sprintf('Username "%s" does not exist.', $username)
            );
        }

        $user = $userProvider->loadUserByUsername($username);

        return new PreAuthenticatedToken(
            $user,
            $username,
            $providerKey,
            $this->rolesMapper->getRoles($user->getGroepen())
        );
    }
}
