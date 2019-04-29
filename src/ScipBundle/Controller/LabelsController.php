<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use ScipBundle\Entity\Label;
use ScipBundle\Form\LabelFilterType;
use ScipBundle\Form\LabelType;
use ScipBundle\Service\LabelDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/labels")
 * @Template
 */
class LabelsController extends AbstractController
{
    protected $title = 'Labels';
    protected $entityName = 'label';
    protected $entityClass = Label::class;
    protected $formClass = LabelType::class;
    protected $filterFormClass = LabelFilterType::class;
    protected $baseRouteName = 'scip_labels_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var LabelDaoInterface
     *
     * @DI\Inject("ScipBundle\Service\LabelDao")
     */
    protected $dao;
}
