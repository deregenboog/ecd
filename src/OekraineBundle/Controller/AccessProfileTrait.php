<?php
namespace OekraineBundle\Controller;

use OekraineBundle\Entity\Verslag;

trait AccessProfileTrait
{
    public function getAccessProfile()
    {
        $accessProfile = Verslag::ACCESS_INLOOP;
        if($this->isGranted("ROLE_OEKRAINE_PSYCH")) {
            $accessProfile = Verslag::ACCESS_PSYCH;
        }
        elseif($this->isGranted("ROLE_MW")) {
            $accessProfile = Verslag::ACCESS_MW;
        }
        return $accessProfile;
    }
}