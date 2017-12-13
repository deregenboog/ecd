<?php

namespace ClipBundle\Controller;

use ClipBundle\Form\VraagFilterType;
use AppBundle\Controller\AbstractChildController;
use ClipBundle\Entity\Vraag;

abstract class AbstractVragenController extends AbstractChildController
{
    protected $entityName = 'vraag';
    protected $entityClass = Vraag::class;
    protected $filterFormClass = VraagFilterType::class;
    protected $baseRouteName = 'clip_vragen_';
}
