<?php

namespace AppBundle\Event;

use AppBundle\Controller\SymfonyController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class KernelSubscriber implements EventSubscriberInterface
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $moduleMapping;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController'],
        ];
    }

    public function __construct(Environment $twig, array $moduleMapping = [])
    {
        $this->twig = $twig;
        $this->moduleMapping = $moduleMapping;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller) && $controller[0] instanceof SymfonyController) {
            $c = $controller[0];
            $this->twig->addGlobal('module', $c->getModule() ?: $this->getDefaultModule($c));
            $this->twig->addGlobal('title', $c->getTitle() ?: $this->getDefaultTitle($c));
            $this->twig->addGlobal('subnavigation', $c->getSubnavigation() ?: $this->getDefaultSubnavigation($c));
            $this->twig->addGlobal('entity_name', $c->getEntityName());
            $this->twig->addGlobal('route_base', $c->getBaseRouteName());
        }
    }

    private function getDefaultModule(SymfonyController $controller)
    {
        $bundle = $this->getBundleName($controller);
        if (array_key_exists($bundle, $this->moduleMapping)) {
            return $this->moduleMapping[$bundle];
        }

        return $bundle;
    }

    private function getDefaultTitle(SymfonyController $controller)
    {
        return $this->getControllerName($controller);
    }

    private function getDefaultSubnavigation(SymfonyController $controller)
    {
        $bundle = $this->getBundleName($controller);
        if ($bundle) {
            return sprintf('@%s/subnavigation.html.twig', $bundle);
        }
    }

    private function getBundleName(SymfonyController $controller)
    {
        $matches = [];
        if (preg_match('/([[:alnum:]]+)Bundle/', get_class($controller), $matches)) {
            return $matches[1];
        }
    }

    private function getControllerName(SymfonyController $controller)
    {
        $matches = [];
        if (preg_match('/([[:alnum:]]+)Controller/', get_class($controller), $matches)) {
            return $matches[1];
        }
    }
}
