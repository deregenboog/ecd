<?php

namespace CakeBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class KernelSubscriber implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \AppController
     */
    private $controller;

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController'],
            KernelEvents::EXCEPTION => ['onKernelException'],
            KernelEvents::VIEW => ['onKernelView'],
        ];
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller) && $controller[0] instanceof \AppController) {
            $this->controller = $controller[0];

            $parts = explode(':', $this->controller->getRequest()->attributes->get('_controller'));
            $this->controller->params['action'] = end($parts);

            $controller[0]->constructClasses();
            $controller[0]->startupProcess();
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundHttpException) {

            // inject controller in base controller used by CakePHP
            \AppController::$staticContainer = $this->container;

            // delegate control to CakePHP
            if (!$this->container->getParameter('kernel.debug')) {
                error_reporting(0);
            }
            $dispatcher = new \Dispatcher();
            $dispatcher->dispatch();
            exit;
        }
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $params = $event->getControllerResult();
        if (is_array($params)) {
            $this->controller->set($params);
        }

        $this->controller->beforeRender();

        $this->setFlashMessage();
        $this->setUserFullName();

        $route = $this->controller->getRequest()->attributes->get('_route');
        $parts = explode('_', $route);
        $parts[0] = '@'.ucfirst($parts[0]);
        $template = implode('/', $parts).'.html.twig';

        $response = $this->container->get('templating')->renderResponse($template, $this->controller->viewVars);

        $event->setResponse($response);
    }

    private function setFlashMessage()
    {
        if ($flash = $this->controller->Session->read('Message.flash')) {
            if (empty($flash['params'])) {
                $this->controller->viewVars['flashMessageSuccess'] = $this->controller->Session->read('Message.flash')['message'];
            } else {
                $this->controller->viewVars['flashMessageError'] = $this->controller->Session->read('Message.flash')['message'];
            }
        }
    }

    private function setUserFullName()
    {
        $user = $this->controller->Session->read('Auth.Medewerker.LdapUser.displayname');
        if (!$user) {
            $user = $this->controller->Session->read('Auth.Medewerker.LdapUser.cn');
        }
        if (!$user) {
            $user = $this->controller->Session->read('Auth.Medewerker.LdapUser.givenname');
        }
        if ($user) {
            $this->controller->viewVars['userFullName'] = $user;
        }
    }
}
