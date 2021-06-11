<?php

namespace OdpBundle\Form;

use InloopBundle\Form\VrijwilligerTypeAbstract;
use OdpBundle\Entity\Vrijwilliger;
use OdpBundle\OdpBundle;


class VrijwilligerType extends VrijwilligerTypeAbstract
{
    protected $dataClass = Vrijwilliger::class;
    protected $locatieSelectClass = LocatieSelectType::class;

}
