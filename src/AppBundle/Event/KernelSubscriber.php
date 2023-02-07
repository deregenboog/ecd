<?php

namespace AppBundle\Event;

use AppBundle\Controller\SymfonyController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
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

    public static function getSubscribedEvents()
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
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof SymfonyController) {
            $this->twig->addGlobal('module', $controller->getModule() ?: $this->getDefaultModule($controller));
            $this->twig->addGlobal('title', $controller->getTitle() ?: $this->getDefaultTitle($controller));
            $this->twig->addGlobal('subnavigation', $controller->getSubnavigation() ?: $this->getDefaultSubnavigation($controller));
            $this->twig->addGlobal('entity_name', $controller->getEntityName());
            $this->twig->addGlobal('route_base', $controller->getBaseRouteName());
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
