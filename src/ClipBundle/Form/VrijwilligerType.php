<?php

namespace ClipBundle\Form;

use ClipBundle\Entity\Vrijwilliger;
use InloopBundle\Form\VrijwilligerTypeAbstract;

class VrijwilligerType extends VrijwilligerTypeAbstract
{
    protected $dataClass = Vrijwilliger::class;
    protected $locatieSelectClass = LocatieSelectType::class;
}
