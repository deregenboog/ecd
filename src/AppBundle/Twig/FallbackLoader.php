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
}
