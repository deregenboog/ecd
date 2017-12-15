<?php

namespace ClipBundle\Report;

use AppBundle\Report\AbstractReport;
use ClipBundle\Service\ContactmomentDaoInterface;
use ClipBundle\Service\VraagDaoInterface;

class VragenPerLeeftijdscategorie extends AbstractReport
{
    protected $title = 'Vragen per leeftijdscategorie';

    protected $xPath = 'kolom';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Leeftijdscategorie';

    protected $tables = [];

    /**
     * @var VraagDaoInterface
     */
    private $vraagDao;

    /**
     * @var ContactmomentDaoInterface
     */
    private $contactmomentDao;

    public function __construct(VraagDaoInterface $vraagDao, ContactmomentDaoInterface $contactmomentDao)
    {
        $this->vraagDao = $vraagDao;
        $this->contactmomentDao = $contactmomentDao;
    }

    protected function init()
    {
        $vragen = $this->vraagDao->countByLeeftijdscategorie($this->startDate, $this->endDate);
        array_walk($vragen, function (&$item) {
            $item['kolom'] = 'Vragen';
        });

        $contactmomenten = $this->contactmomentDao->countByLeeftijdscategorie($this->startDate, $this->endDate);
        array_walk($contactmomenten, function (&$item) {
            $item['kolom'] = 'Contactmomenten';
        });

        $this->tables[''] = array_merge($vragen, $contactmomenten);
    }
}
