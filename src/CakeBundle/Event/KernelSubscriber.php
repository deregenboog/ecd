<?php

namespace CakeBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Controller\AbstractController;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;

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
            KernelEvents::REQUEST => ['onKernelRequest'],
            KernelEvents::CONTROLLER => ['onKernelController'],
            KernelEvents::EXCEPTION => ['onKernelException'],
            KernelEvents::VIEW => ['onKernelView'],
        ];
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->setLdapConfiguration();

        // @todo remove this when no longer needed
        $em = $this->container->get('doctrine.orm.entity_manager');

        $queries = [];
        foreach ([Klant::class, Vrijwilliger::class] as $model) {
            foreach (['werkgebied', 'postcodegebied'] as $property) {
                $queries[] = "UPDATE {$model} AS model SET model.{$property} = NULL WHERE model.{$property} = ''";
            }
        }

        foreach ($queries as $query) {
            $em->createQuery($query)->execute();
        }
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller) && $controller[0] instanceof \AppController) {
            $this->controller = $controller[0];

            $parts = explode(':', $this->controller->getRequest()->attributes->get('_controller'));
            $this->controller->params['action'] = end($parts);

            $this->controller->constructClasses();
            $this->controller->startupProcess();
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof NotFoundHttpException
            && (0 === strpos($exception->getMessage(), 'No route found for ')
                || 0 === strpos($exception->getMessage(), 'Unable to find the controller for path ')
            )
        ) {
            // inject controller in base controller used by CakePHP
            \AppController::$staticContainer = $this->container;
            $this->setLdapConfiguration();

            // adjust error level
            if ('dev' === $this->container->getParameter('kernel.environment')
                && $this->container->getParameter('kernel.debug')
            ) {
                Debug::enable(E_ALL & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE, true);
            }

            // delegate control to CakePHP
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

        $bundle = '@'.ucfirst(reset($parts));
        $path = next($parts);
        $template = next($parts);

        if ($this->controller instanceof AbstractController && $this->controller->getTemplatePath()) {
            $path = $this->controller->getTemplatePath();
        }

        $location = implode('/', [$bundle, $path, $template]).'.html.twig';
        $response = $this->container->get('templating')->renderResponse($location, $this->controller->viewVars);

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

    private function setLdapConfiguration()
    {
        \Configure::write('LDAP.configuration', [
            'active_directory' => $this->container->getParameter('ldap_active_directory'),
            'host' => $this->container->getParameter('ldap_host'),
            'port' => $this->container->getParameter('ldap_port'),
            'baseDn' => $this->container->getParameter('ldap_base_dn'),
            'username' => $this->container->getParameter('ldap_search_user'),
            'password' => $this->container->getParameter('ldap_search_password'),
        ]);
    }
}
