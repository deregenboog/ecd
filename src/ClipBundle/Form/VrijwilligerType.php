<?php

namespace ClipBundle\Form;

use InloopBundle\Form\VrijwilligerTypeAbstract;
use ClipBundle\Entity\Vrijwilliger;
use ClipBundle\ClipBundle;


class VrijwilligerType extends VrijwilligerTypeAbstract
{
    protected $dataClass = Vrijwilliger::class;
    protected $locatieSelectClass = LocatieSelectType::class;

}
