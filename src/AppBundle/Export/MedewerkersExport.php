<?php

namespace AppBundle\Export;

use AppBundle\Service\AbstractDao;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class MedewerkersExport extends GenericExport
{

    private $roleHierarchy;

    public function __construct($class, array $configuration, array $roleHierarchy, $friendlyName=null, $dao=null)
    {
        $this->roleHierarchy = $roleHierarchy;
        parent::__construct($class,$configuration,$friendlyName,$dao);
    }

    public function getRoleHierarchy($entity)
    {
        $roles = $entity->getRoles();
        if(!is_array($roles)) return '';
        $r = '';

        foreach($roles as $role) {
            $r .= "Rol: ".$role."\r\n";
            if (array_key_exists($role, $this->roleHierarchy))
            {
                $subRoles = $this->roleHierarchy[$role];
                foreach($subRoles as $subsubRole)
                {
                    $r .= "\t".$subsubRole."\r\n";
                    if(array_key_exists($subsubRole,$this->roleHierarchy))
                    {
                        $subsubsub = $this->roleHierarchy[$subsubRole];
                        $r .= "\t\t".implode(", ",$subsubsub)."\r\n";
                    }
                }

            }

        }
        return $r;
    }
}
