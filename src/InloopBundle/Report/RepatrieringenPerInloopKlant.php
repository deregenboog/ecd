<?php

namespace InloopBundle\Report;

use AppBundle\Entity\Klant;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\AbstractSqlFileReport;
use AppBundle\Report\Table;
use Doctrine\ORM\EntityManagerInterface;


class RepatrieringenPerInloopKlant extends AbstractSqlFileReport
{

    protected $title = 'Repatriëringen per inloopklant';

}
