<?php

namespace AppBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Service\BehandelaarDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\Doelgroep;
use IzBundle\Form\DoelgroepType;
use IzBundle\Service\DoelgroepDaoInterface;
use IzBundle\Entity\ContactOntstaan;
use AppBundle\Entity\ZrmV2;
use AppBundle\Service\ZrmDaoInterface;
use AppBundle\Form\ZrmType;
use AppBundle\Entity\Zrm;
use AppBundle\Form\ZrmFilterType;

/**
 * @Route("/zrms")
 */
class ZrmsController extends AbstractChildController
{
    protected $title = 'Zelfredzaamheidmatrixen';
    protected $entityName = 'zelfredzaamheidmatrix';
    protected $entityClass = Zrm::class;
    protected $formClass = ZrmType::class;
    protected $filterFormClass = ZrmFilterType::class;
    protected $baseRouteName = 'app_zrms_';
    protected $addMethod = 'addZrm';

    /**
     * @var ZrmDaoInterface
     *
     * @DI\Inject("AppBundle\Service\ZrmDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("app.zrm.entities")
     */
    protected $entities;

    protected function createEntity($parentEntity)
    {
        return Zrm::create();
    }

    protected function beforeParentUpdate($parentEntity, $entity)
    {
        $entity
            ->setModel('Klant')
            ->setForeignKey($parentEntity->getId())
        ;
    }
}
