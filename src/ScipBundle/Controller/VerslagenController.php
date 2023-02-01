<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use JMS\DiExtraBundle\Annotation as DI;
use ScipBundle\Entity\Verslag;
use ScipBundle\Form\VerslagType;
use ScipBundle\Service\VerslagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/verslagen")
 */
class VerslagenController extends AbstractChildController
{
    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addVerslag';
    protected $baseRouteName = 'scip_verslagen_';
    protected $disabledActions = ['index', 'view'];

    /**
     * @var VerslagDaoInterface
     *
     * @DI\Inject("ScipBundle\Service\VerslagDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("scip.verslag.entities")
     */
    protected $entities;

    protected function beforeCreate($entity)
    {
        $this->updateEvaluatieDatum($entity);
        parent::beforeCreate($entity);
    }

    protected function beforeUpdate($entity)
    {
        $this->updateEvaluatieDatum($entity);
        parent::beforeUpdate($entity);
    }

    protected function updateEvaluatieDatum(Verslag $entity)
    {
        //Verslag is geen evaluatieverslag
        if(!$entity->isEvaluatie()) return;

        //Versag is evaluatieverslag, maar de datum ligt te ver in het verleden - voordat er geevalueerd moet worden. Doe niks, maar doe wel melding
        if($entity->getDeelnemer()->getEvaluatiedatum() !== null && $entity->getDatum() < $entity->getDeelnemer()->getEvaluatiedatum()->modify("-1 week") )
        {
            $this->addFlash('warning', 'Evaluatiedatum niet verwijderd, verslagdatum ligt meer dan een week voor evaluatiedatum.');
            return;
        }

        $entity->getDeelnemer()->setEvaluatiedatum(null);
    }
}
