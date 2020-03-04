<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use HsBundle\Entity\Betaling;
use HsBundle\Form\BetalingFilterType;
use HsBundle\Form\BetalingType;
use HsBundle\Service\BetalingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/betalingen")
 * @Template
 */
class BetalingenController extends AbstractChildController
{
    protected $title = 'Betalingen';
    protected $entityName = 'betaling';
    protected $entityClass = Betaling::class;
    protected $formClass = BetalingType::class;
    protected $filterFormClass = BetalingFilterType::class;
    protected $addMethod = 'addBetaling';
    protected $baseRouteName = 'hs_betalingen_';

    /**
     * @var BetalingDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("HsBundle\Service\BetalingDao");
        $this->entities = $container->get("hs.betaling.entities");
        $this->export = $container->get("hs.export.betaling");
    
        return $previous;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
