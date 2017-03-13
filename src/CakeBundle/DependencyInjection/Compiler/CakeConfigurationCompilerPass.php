<?php

namespace CakeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use CakeBundle\Service\CakeConfiguration;

class CakeConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        /* @var $config CakeConfiguration */
        $config = $container->get('cake.service.config');

        /* @var $params array */
        $params = $container->getParameter('cake');

        /*
         * CakePHP Debug Level:.
         *
         * Production Mode:
         * 	0: No error messages, errors, or warnings shown. Flash messages redirect.
         *
         * Development Mode:
         * 	1: Errors and warnings shown, model caches refreshed, flash messages halted.
         * 	2: As in 1, but also with full debug messages and SQL output.
         *
         * In production mode, flash messages redirect after a time interval.
         * In development mode, you need to click the flash message to continue.
         */
        $config->set('debug', $container->getParameter('kernel.debug') ? $params['debug_level'] : 0);

        /*
         * Turn off all caching application-wide.
         *
         */
        $config->set('Cache.disable', $container->getParameter('kernel.debug'));

        $config->set('ACL.volonteers', $params['volunteers']);
        $config->set('ACL.groups', $params['ACL.groups']);
        // define constants for acl groups
        foreach ($params['ACL.groups'] as $name => $id) {
            define($name, $id);
        }

        // convert group names to group ids
        foreach ($params['ACL.permissions'] as $key => $value) {
            $params['ACL.permissions'][constant($key)] = $value;
            unset($params['ACL.permissions'][$key]);
        }
        $config->set('ACL.permissions', $params['ACL.permissions']);

        /* Disable ACL with a flag. This only works in debug mode. */
        $config->set('ACL.disabled', $container->getParameter('acl_disabled') && $container->getParameter('kernel.debug'));

        $container->setParameter('all_menu_items', $params['all_menu_items']);

        $config->set('TBC_months_period', $params['TBC_months_period']);

        // aanwezig, afwezig
        $config->set('Afmeldstatus', $params['Afmeldstatus']);
        $config->set('Postcodegebieden', $params['Postcodegebieden']);
        $config->set('Werkgebieden', $params['Werkgebieden']);
        $config->set('Persoontypen', $params['Persoontypen']);
        $config->set('IzFase', $params['IzFase']);
        $config->set('Communicatietypen', $params['Communicatietypen']);
        $config->set('options_medewerker', $params['options_medewerker']);

        // list of klant countries indicating that the klant should be sent to AMOC
        $config->set('Landen.AMOC', $params['Landen.AMOC']);

        /*
         * Setting language to Dutch for month selection fields.
         */
        $config->set('Config.language', $params['Config.language']);
        $config->set('Calendar.dateDisplayFormat', $params['Calendar.dateDisplayFormat']);

        // e-mail addresses
        $config->set('informele_zorg_mail', $params['email']['informele_zorg']);
        $config->set('dagbesteding_mail', $params['email']['dagbesteding']);
        $config->set('inloophuis_mail', $params['email']['inloophuis']);
        $config->set('hulpverlening_mail', $params['email']['hulpverlening']);
        $config->set('agressie_mail', $params['email']['agressie']);
        $config->set('administratiebedrijf', $params['email']['administratiebedrijf']);

        $container->register('cake.configuration', CakeConfiguration::class)
            ->addArgument($config->all());
    }
}
