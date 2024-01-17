<?php

namespace AppBundle\Report;

use Doctrine\ORM\EntityManagerInterface;

class ManagementMW extends AbstractSqlFileReport
{
    protected $title = 'Managementrapportage MW';

    public function __construct(EntityManagerInterface $em, $sqlFile)
    {
        $this->params += [
            ':locatietypes' => "'".implode("','",[
                'Maatschappelijk werk',
                'Wachtlijst',
                'Virtueel',
            ])."'",
        ];
        parent::__construct($em, $sqlFile);
    }

}
