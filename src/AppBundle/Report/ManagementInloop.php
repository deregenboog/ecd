<?php

namespace AppBundle\Report;

use Doctrine\ORM\EntityManagerInterface;

class ManagementInloop extends AbstractSqlFileReport
{
    protected $title = 'Managementrapportage Inloop';

    public function __construct(EntityManagerInterface $em, $sqlFile)
    {
        $this->params += [
          ':locatietypes' => "'".implode("','",[
              'Inloop',
          ])."'",
        ];
        parent::__construct($em, $sqlFile);
    }


}
