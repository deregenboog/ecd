<?php

namespace OekraineBundle\Service;

use OekraineBundle\Entity\Vrijwilliger;

class VrijwilligerDao extends VrijwilligerDaoAbstract implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'appVrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'appVrijwilliger.id',
            'appVrijwilliger.voornaam',
            'appVrijwilliger.achternaam',
            'vrijwilliger.aanmelddatum',
            'vrijwilliger.afsluitdatum',
            'locatie.naam',
            'werkgebied.naam',
        ],
    ];

    protected $class = Vrijwilliger::class;

    protected $alias = 'vrijwilliger';
}
