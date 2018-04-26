<?php

namespace IzBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Intake;
use AppBundle\Controller\AbstractChildController;
use IzBundle\Form\IntakeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IzBundle\Service\IntakeDaoInterface;
use AppBundle\Entity\Zrm;

/**
 * @Route("/intakes")
 */
class IntakesController extends AbstractChildController
{
    protected $title = 'Intakes';
    protected $entityName = 'intake';
    protected $entityClass = Intake::class;
    protected $formClass = IntakeType::class;
    protected $addMethod = 'setIntake';
    protected $baseRouteName = 'iz_intakes_';
    protected $disabledActions = ['index'];

    /**
     * @var IntakeDaoInterface
     *
     * @DI\Inject("IzBundle\Service\IntakeDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("iz.intake.entities")
     */
    protected $entities;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.klanten")
     */
    protected $export;

    /**
     * @Template("IzBundle:intakes:_zrms.html.twig")
     */
    public function _zrmsAction($id)
    {
        $entity = $this->dao->find($id);

        $zrms = $this->getEntityManager()->getRepository(Zrm::class)->createQueryBuilder('zrm')
            ->where('zrm.model IN (:models) AND zrm.foreignKey = :fk')
            ->setParameters([
                'models' => ['IzIntake', Intake::class],
                'fk' => $entity->getId(),
            ])
            ->getQuery()
            ->getResult()
        ;

        return [
            'intake' => $entity,
            'zrms' => $zrms,
        ];
    }
}
