<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Zrm;
use AppBundle\Form\ZrmFilterType;
use AppBundle\Form\ZrmType;
use AppBundle\Service\ZrmDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct()
    {
        $this->dao = $this->get("AppBundle\Service\ZrmDao");
        $this->entities = $this->get("app.zrm.entities");
    }

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
