<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use ClipBundle\Service\ContactmomentDaoInterface;

class VragenPerCommunicatiekanaal extends AbstractReport
{
    protected $title = 'Vragen per contacttype';

    protected $xPath = 'kolom';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Contacttype';

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
        $vragen = $this->vraagDao->countByCommunicatiekanaal($this->startDate, $this->endDate);
        array_walk($vragen, function (&$item) {
            $item['kolom'] = 'Vragen';
        });

        $contactmomenten = $this->contactmomentDao->countByCommunicatiekanaal($this->startDate, $this->endDate);
        array_walk($contactmomenten, function (&$item) {
            $item['kolom'] = 'Contactmomenten';
        });

        $this->tables[''] = array_merge($vragen, $contactmomenten);
    }
}
