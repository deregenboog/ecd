<?php

namespace AppBundle\Twig;

use Doctrine\Persistence\Proxy;
use Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser as BaseTemplateGuesser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class TemplateGuesser extends BaseTemplateGuesser
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var string[]
     */
    private $controllerPatterns;

    /**
     * @param string[] $controllerPatterns Regexps extracting the controller name from its FQN
     */
    public function __construct(KernelInterface $kernel, array $controllerPatterns = [])
    {
        $this->kernel = $kernel;
        $this->controllerPatterns = $controllerPatterns;
    }

    /**
     * Guesses and returns the template name to render based on the controller
     * and action names.
     *
     * @param callable $controller An array storing the controller object and action method
     *
     * @return string The template name
     *
     * @throws \InvalidArgumentException
     */
    public function guessTemplateName($controller, Request $request)
    {
        $baseGuesser = new BaseTemplateGuesser($this->kernel, $this->controllerPatterns);
        $template = $baseGuesser->guessTemplateName($controller, $request);

        if (\is_object($controller) && method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        } elseif (!\is_array($controller)) {
            throw new \InvalidArgumentException(sprintf('First argument of "%s" must be an array callable or an object defining the magic method __invoke. "%s" given.', __METHOD__, \gettype($controller)));
        }

        $className = $this->getRealClass(\get_class($controller[0]));

        $patterns = [
            '/(.+)Bundle\\\Controller\\\.+Controller$/',
            '/(.+)\\\Controller\\\.+Controller$/',
        ];

        $namespace = null;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $className, $tempMatch)) {
                $namespace = $tempMatch[1];
                break;
            }
        }

        return ($namespace ? '@'.$namespace.'/' : '').$template;
    }

    private static function getRealClass(string $class): string
    {
        if (!class_exists(Proxy::class)) {
            return $class;
        }
        if (false === $pos = strrpos($class, '\\'.Proxy::MARKER.'\\')) {
            return $class;
        }

        return substr($class, $pos + Proxy::MARKER_LENGTH + 2);
    }
}
