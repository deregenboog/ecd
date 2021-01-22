<?php

namespace MwBundle\Form;

use InloopBundle\Form\VrijwilligerTypeAbstract;
use MwBundle\Entity\Vrijwilliger;
use MwBundle\MwBundle;


class VrijwilligerType extends VrijwilligerTypeAbstract
{
    protected $dataClass = Vrijwilliger::class;
}
