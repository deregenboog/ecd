<?php

namespace GaBundle\Controller;

use GaBundle\Entity\KlantVerslag;
use GaBundle\Entity\Verslag;
use GaBundle\Form\VerslagType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/klantverslagen")
 * @Template
 */
class KlantverslagenController extends VerslagenController
{
    protected $entityClass = KlantVerslag::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'ga_klantverslagen_';

    /**
     * @var VerslagDaoInterface
     *
     * @DI\Inject("GaBundle\Service\KlantVerslagDao")
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
     */
    public function viewAction(Request $request, $id)
    {
        $verslag = $this->dao->find($id);
        $intake = $this->get('GaBundle\Service\KlantIntakeDao')->findOneByKlant($verslag->getKlant());

        return $this->redirectToRoute('ga_klanten_view', [
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
