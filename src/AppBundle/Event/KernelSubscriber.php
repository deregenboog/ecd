<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use AppBundle\Controller\SymfonyController;

class KernelSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController'],
        ];
    }

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller) && $controller[0] instanceof SymfonyController) {
            $this->twig->addGlobal('title', $controller[0]->getTitle());
            $this->twig->addGlobal('entity_name', $controller[0]->getEntityName());
            $this->twig->addGlobal('route_base', $controller[0]->getBaseRouteName());
        }
    }
}
