<?php

namespace GaBundle\Service;

use GaBundle\Entity\Groep;
use GaBundle\Entity\VrijwilligerLidmaatschap;

class VrijwilligerLidmaatschapDao extends LidmaatschapDao
{
    protected $paginationOptions = [
        'pageParameterName' => 'page_vrijwilliger',
        'sortFieldParameterName' => 'sort_vrijwilliger',
        'sortDirectionParameterName' => 'direction_vrijwilliger',
        //         'defaultSortFieldName' => 'groep.naam',
    //         'defaultSortDirection' => 'asc',
    //         'sortFieldWhitelist' => [
        //             'groep.naam',
        //             'groep.startdatum',
        //             'groep.einddatum',
        //             'werkgebied.naam',
        //         ],
    ];

    protected $class = VrijwilligerLidmaatschap::class;
}
