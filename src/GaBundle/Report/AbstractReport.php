<?php

namespace GaBundle\Report;

use AppBundle\Report\AbstractReport as BaseAbstractReport;
use GaBundle\Service\GroupTypeContainer;

abstract class AbstractReport extends BaseAbstractReport
{
    /**
     * @var GroupTypeContainer
     */
    protected $groupTypes;

    protected array $data;

    public function __construct(GroupTypeContainer $groupTypes)
    {
        $this->groupTypes = $groupTypes;
    }
}
