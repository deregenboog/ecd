<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Twig\Extension\RoutingExtension as BaseRoutingExtension;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RoutingExtension extends BaseRoutingExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(
        UrlGeneratorInterface $generator,
        RequestStack $requestStack
    ) {
        $this->generator = $generator;
        $this->requestStack = $requestStack;
        parent::__construct($generator);
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $relative
     *
     * @return string
     */
    public function getPath($name, $parameters = [], $relative = false)
    {
        if (!array_key_exists('redirect', $parameters)) {
            $parameters['redirect'] = '/'.$this->requestStack->getMasterRequest()->get('url');
        }

        return parent::getPath($name, $parameters, $relative);
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $schemeRelative
     *
     * @return string
     */
    public function getUrl($name, $parameters = [], $schemeRelative = false)
    {
        if (!array_key_exists('redirect', $parameters)) {
            $parameters['redirect'] = $this->requestStack->getCurrentRequest()->get('url');
        }

        return parent::getUrl($name, $parameters, $relative);
    }
}
