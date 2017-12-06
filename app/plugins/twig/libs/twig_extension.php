<?php
/**
 * TwigView for CakePHP.
 *
 * @version 0.8.0
 *
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @license MIT License
 *
 * @see http://www.twig-project.org Twig Homepage
 * @see http://github.com/m3nt0r My GitHub
 * @see http://twitter.com/m3nt0r My Twitter
 */
/**
 * TwigView_Extension
 * Inherit for Custom Filter Extensions.
 *
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 */
abstract class TwigView_Extension
{
    /**
     * Instance and register any given class.
     *
     * @author Kjell Bublitz
     *
     * @param string $className
     *
     * @return object
     */
    protected static function helperObject($className)
    {
        $registryKey = 'TwigView_Extension_'.$className;
        $object = ClassRegistry::getObject($registryKey);
        if (is_a($object, $className)) {
            return $object;
        }
        $object = new $className();
        ClassRegistry::addObject($registryKey, $object);

        return $object;
    }
}
