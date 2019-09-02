<?php

namespace MwBundle\Report;

use AppBundle\Entity\Klant;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\AbstractSqlFileReport;
use AppBundle\Report\Table;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Afsluiting;

class RepatrieringenPerKlant extends AbstractSqlFileReport
{

    protected $title = 'Repatriëringen per klant';

}
