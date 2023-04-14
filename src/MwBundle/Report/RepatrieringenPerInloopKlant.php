<?php

namespace MwBundle\Report;

use AppBundle\Entity\Klant;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\AbstractSqlFileReport;
use AppBundle\Report\Table;
use Doctrine\ORM\EntityManager;


class RepatrieringenPerInloopKlant extends AbstractSqlFileReport
{

    protected $title = 'Repatriëringen per inloopklant';

}
