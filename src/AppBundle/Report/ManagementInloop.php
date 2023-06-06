<?php

namespace AppBundle\Report;

use Doctrine\ORM\EntityManager;

class ManagementInloop extends AbstractSqlFileReport
{
    protected $title = 'Managementrapportage Inloop';

    public function __construct(EntityManager $em, $sqlFile)
    {
        $this->params += [
          ':locatietypes' => "'".implode("','",[
              'Inloop',
          ])."'",
        ];
        parent::__construct($em, $sqlFile);
    }


}
