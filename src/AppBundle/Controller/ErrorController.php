<?php

namespace AppBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function show(Request $request, Throwable $exception): Response
    {
        if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() === 403) {
            $uri = $request->getRequestUri();
            $user = $this->getUser()?->getUserIdentifier() ?? 'anoniem';

            // refactoren
            if (
                str_starts_with($uri, '/vrijwilligers') ||
                str_starts_with($uri, '/klanten')
            ) {

                $this->logger->warning('Toegang geweigerd, te weinig rechten', [
                    'url' => $uri,
                    'user' => $user,
                    'reden' => $exception->getMessage()
                ]);

                return $this->render('security/access_denied.html.twig', [], new Response('', 403));
            }

            // Alle andere 403's, laad standaard 403 template
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig', [], new Response('', 403));
        }

        // overige error afhandeling symfony
        throw $exception;
    }
}
