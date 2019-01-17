<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Zrm;
use AppBundle\Form\ZrmFilterType;
use AppBundle\Form\ZrmType;
use AppBundle\Service\ZrmDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/zrms")
 * @Template
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

    protected function createEntity($parentEntity = null)
    {
        return Zrm::create(
            new \DateTime(),
            $this->getRequest()->get('module', 'Klant')
        );
    }

    protected function beforeParentUpdate($parentEntity, $entity)
    {
        $entity
            ->setModel('Klant')
            ->setForeignKey($parentEntity->getId())
        ;
    }
}
