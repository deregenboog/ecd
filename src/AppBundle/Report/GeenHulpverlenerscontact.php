<?php

namespace AppBundle\Report;

use AppBundle\Entity\Geslacht;
use InloopBundle\Entity\Locatie;

class GeenHulpverlenerscontact extends AbstractSqlFileReport
{
    protected $title = 'Geen hulpverlenerscontact';

    /**
     * @var Locatie
     */
    protected $locatie;

    /**
     * @var Geslacht
     */
    protected $geslacht;

    public function getFormOptions()
    {
        return [
            'enabled_filters' => [
                'locatie',
                'startdatum',
                'einddatum',
                'geslacht',
            ],
        ];
    }

    public function setFilter(array $filter)
    {
        if (array_key_exists('locatie', $filter)) {
            $this->locatie = $filter['locatie'];
        }

        if (array_key_exists('geslacht', $filter)) {
            $this->geslacht = $filter['geslacht'];
        }

        return parent::setFilter($filter);
    }

    protected function replacePlaceholders($sql)
    {
        if (!$this->locatie instanceof Locatie) {
            return false;
        }

        $search = [
            ':from',
            ':until',
            ':location',
        ];

        $replace = [
            sprintf("'%s'", $this->startDate->format('Y-m-d')),
            sprintf("'%s'", $this->endDate->format('Y-m-d')),
            (int) $this->locatie->getId(),
        ];

        if (!$this->geslacht instanceof Geslacht) {
            $sql = str_ireplace('and k.geslacht_id in (:gender)', '', $sql);
        } else {
            $search[] = ':gender';
            $replace[] = (int) $this->geslacht->getId();
        }

        return str_replace($search, $replace, $sql);
    }
}
