<?php

declare(strict_types=1);

namespace Tests\AppBundle\Security;

use AppBundle\Security\DevelopmentAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class DevelopmentAuthenticatorTest extends TestCase
{
    public function testDoesNotSupportProdEnv()
    {
        $router = $this->createMock(RouterInterface::class);
        $devAuthenticator = new DevelopmentAuthenticator('prod', $router);
        $this->assertFalse($devAuthenticator->supports(new Request()));
    }

    public function testDoesNotSupportPublicAddress()
    {
        $router = $this->createMock(RouterInterface::class);
        $devAuthenticator = new DevelopmentAuthenticator('dev', $router);

        $request = Request::create('/', 'POST', [], [], [], ['SERVER_ADDR' => '8.8.8.8']);
        $this->assertFalse($devAuthenticator->supports($request));
    }

    public function testDoesNotSupportRequestWithoutUsername()
    {
        $router = $this->createMock(RouterInterface::class);
        $devAuthenticator = new DevelopmentAuthenticator('dev', $router);

        $request = Request::create('/', 'POST', [], [], [], ['SERVER_ADDR' => '127.0.0.1']);
        $this->assertFalse($devAuthenticator->supports($request));
    }

    public function testSupportsDevEnv()
    {
        $router = $this->createMock(RouterInterface::class);
        $devAuthenticator = new DevelopmentAuthenticator('dev', $router);

        $request = Request::create('/', 'POST', ['_username' => 'admin'], [], [], ['SERVER_ADDR' => '127.0.0.1']);
        $this->assertTrue($devAuthenticator->supports($request));
    }

    public function testCannotAuthenticateWithoutPassword()
    {
        $router = $this->createMock(RouterInterface::class);
        $devAuthenticator = new DevelopmentAuthenticator('dev', $router);

        $this->expectException(CustomUserMessageAuthenticationException::class);
        $request = Request::create('/', 'POST', ['_username' => 'admin'], [], [], ['SERVER_ADDR' => '127.0.0.1']);
        $devAuthenticator->authenticate($request);
    }

    public function testAuthenticate()
    {
        $router = $this->createMock(RouterInterface::class);
        $devAuthenticator = new DevelopmentAuthenticator('dev', $router);

        $request = Request::create('/', 'POST', ['_username' => 'admin', '_password' => 'admin'], [], [], ['SERVER_ADDR' => '127.0.0.1']);
        /** @var Passport $passport */ $passport = $devAuthenticator->authenticate($request);
        /** @var UserBadge $userBadge */ $userBadge = $passport->getBadge(UserBadge::class);
        $this->assertInstanceOf(UserBadge::class, $userBadge);
        $this->assertEquals('admin', $userBadge->getUserIdentifier());
        $credentialsBadge = $passport->getBadge(CustomCredentials::class); /* @var CustomCredentials $credentialsBadge */
        $this->assertInstanceOf(CustomCredentials::class, $credentialsBadge);
    }

    public function testStart()
    {
        $router = $this->createMock(RouterInterface::class);
        $router->expects($this->once())->method('generate')->with('login')->willReturn('/login');
        $devAuthenticator = new DevelopmentAuthenticator('dev', $router);

        $request = Request::create('/', 'POST', ['_username' => 'admin', '_password' => 'admin'], [], [], ['SERVER_ADDR' => '127.0.0.1']);
        $devAuthenticator->start($request);
    }

    public function testOnAuthenticationSuccess()
    {
        $router = $this->createMock(RouterInterface::class);
        $router->expects($this->once())->method('generate')->with('home')->willReturn('/');
        $devAuthenticator = new DevelopmentAuthenticator('dev', $router);

        $request = Request::create('/', 'POST', ['_username' => 'admin', '_password' => 'admin'], [], [], ['SERVER_ADDR' => '127.0.0.1']);
        $response = $devAuthenticator->onAuthenticationSuccess($request, $this->createMock(TokenInterface::class), 'provider_key');
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testOnAuthenticationFailure()
    {
        $router = $this->createMock(RouterInterface::class);
        $devAuthenticator = new DevelopmentAuthenticator('dev', $router);

        $request = Request::create('/', 'POST', ['_username' => 'admin', '_password' => 'admin'], [], [], ['SERVER_ADDR' => '127.0.0.1']);
        $response = $devAuthenticator->onAuthenticationFailure($request, $this->createMock(AuthenticationException::class));
        $this->assertNull($response);
    }
}
