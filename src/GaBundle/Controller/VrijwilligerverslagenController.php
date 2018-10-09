<?php

namespace GaBundle\Controller;

use GaBundle\Entity\Verslag;
use GaBundle\Entity\VrijwilligerVerslag;
use GaBundle\Form\VerslagType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/vrijwilligerverslagen")
 */
class VrijwilligerverslagenController extends VerslagenController
{
    protected $entityClass = VrijwilligerVerslag::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'ga_vrijwilligerverslagen_';

    /**
     * @var VerslagDaoInterface
     *
     * @DI\Inject("GaBundle\Service\VrijwilligerVerslagDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("ga.verslag.entities")
     */
    protected $entities;

    /**
     * @Route("/{id}/view")
     * @Template
     */
    public function viewAction(Request $request, $id)
    {
        $verslag = $this->dao->find($id);
        $intake = $this->get('GaBundle\Service\VrijwilligerIntakeDao')->findOneByVrijwilliger($verslag->getVrijwilliger());

        return $this->redirectToRoute('ga_vrijwilligers_view', [
            'id' => $intake->getId(),
            '_fragment' => 'verslagen',
        ]);
    }

    protected function createEntity($parentEntity = null)
    {
        return new $this->entityClass($parentEntity);
    }

    protected function persistEntity($entity, $parentEntity)
    {
        $this->dao->create($entity);
    }
}
