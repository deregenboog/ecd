<?php

namespace AppBundle\Twig;

use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

class FallbackLoader extends FilesystemLoader
{
    /**
     * @return string|null
     */
    protected function findTemplate($name, $throw = true)
    {
        $name = $this->normalizeName($name);
        $parts = explode('/', $name);

        array_shift($parts);
        $shortname = implode('/', $parts);

        $template = parent::findTemplate($shortname, false);
        if ($template) {
            return $template;
        }

        array_shift($parts);
        $shortname = implode('/', $parts);

        return parent::findTemplate($shortname, $throw);
    }

    private function normalizeName(string $name): string
    {
        return preg_replace('#/{2,}#', '/', str_replace('\\', '/', $name));
    }

    private function parseName(string $name, string $default = self::MAIN_NAMESPACE): array
    {
        if (isset($name[0]) && '@' == $name[0]) {
            if (false === $pos = strpos($name, '/')) {
                throw new LoaderError(sprintf('Malformed namespaced template name "%s" (expecting "@namespace/template_name").', $name));
            }

            $namespace = substr($name, 1, $pos - 1);
            $shortname = substr($name, $pos + 1);

            return [$namespace, $shortname];
        }

        return [$default, $name];
    }
}
