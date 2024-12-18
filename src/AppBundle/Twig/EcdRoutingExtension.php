<?php

namespace AppBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\TwigFunction;

class EcdRoutingExtension extends AbstractExtension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(
        RouterInterface $router,
        RequestStack $requestStack
    ) {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('route_exists', [$this, 'routeExists']),
            new TwigFunction('url', [$this, 'getUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('path', [$this, 'getPath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
        ];
    }

    public function routeExists($name)
    {
        return (null === $this->router->getRouteCollection()->get($name)) ? false : true;
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
            $url = $this->requestStack->getCurrentRequest()->getPathInfo();

            if ($url = '_fragment') {
                $url = $this->requestStack->getMainRequest()->getPathInfo();
            }
            $parameters['redirect'] = $url;
        }

        return $this->_getPath($name, $parameters, $relative);
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $relative
     *
     * @return string
     */
    public function _getPath($name, $parameters = [], $relative = false)
    {
        return $this->router->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
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

        return $this->_getUrl($name, $parameters, $schemeRelative);
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $schemeRelative
     *
     * @return string
     */
    public function _getUrl($name, $parameters = [], $schemeRelative = false)
    {
        return $this->router->generate($name, $parameters, $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * Determines at compile time whether the generated URL will be safe and thus
     * saving the unneeded automatic escaping for performance reasons.
     *
     * The URL generation process percent encodes non-alphanumeric characters. So there is no risk
     * that malicious/invalid characters are part of the URL. The only character within an URL that
     * must be escaped in html is the ampersand ("&") which separates query params. So we cannot mark
     * the URL generation as always safe, but only when we are sure there won't be multiple query
     * params. This is the case when there are none or only one constant parameter given.
     * E.g. we know beforehand this will be safe:
     * - path('route')
     * - path('route', {'param': 'value'})
     * But the following may not:
     * - path('route', var)
     * - path('route', {'param': ['val1', 'val2'] }) // a sub-array
     * - path('route', {'param1': 'value1', 'param2': 'value2'})
     * If param1 and param2 reference placeholder in the route, it would still be safe. But we don't know.
     *
     * @param Node $argsNode The arguments of the path/url function
     *
     * @return array An array with the contexts the URL is safe
     *
     * @final
     */
    public function isUrlGenerationSafe(Node $argsNode): array
    {
        // support named arguments
        $paramsNode = $argsNode->hasNode('parameters') ? $argsNode->getNode('parameters') : (
            $argsNode->hasNode(1) ? $argsNode->getNode(1) : null
        );

        if (null === $paramsNode || $paramsNode instanceof ArrayExpression && \count($paramsNode) <= 2
            && (!$paramsNode->hasNode(1) || $paramsNode->getNode(1) instanceof ConstantExpression)
        ) {
            return ['html'];
        }

        return [];
    }

    public function getName(): string
    {
        return 'routing';
    }
}
