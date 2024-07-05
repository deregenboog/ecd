<?php

namespace ClipBundle\Form;

use ClipBundle\Entity\Vrijwilliger;
use InloopBundle\Form\VrijwilligerCloseTypeAbstract;

class VrijwilligerCloseType extends VrijwilligerCloseTypeAbstract
{
    protected $class = Vrijwilliger::class;
}
