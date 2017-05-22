<?php

namespace CakeBundle\Service;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\ClassLoader\MapClassLoader;

class CakeCacheWarmer implements CacheWarmerInterface
{
    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../../cake/libs');

        $classMap = [];
        foreach ($finder as $file) {
            $phpCode = file_get_contents($file->getRealPath());
            $classes = $this->getClasses($phpCode);
            foreach ($classes as $class) {
                $classMap[$class] = $file->getRealPath();
            }
        }

        $loader = new MapClassLoader($classMap);
        $loader->register();
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return false;
    }

    private function getClasses($phpCode)
    {
        $classes = [];
        $tokens = token_get_all($phpCode);
        for ($i = 2; $i < count($tokens); ++$i) {
            if ($tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING) {
                $className = $tokens[$i][1];
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
