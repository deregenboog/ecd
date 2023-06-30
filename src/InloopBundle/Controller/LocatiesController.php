<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use InloopBundle\Entity\Locatie;
use InloopBundle\Form\LocatieFilterType;
use InloopBundle\Form\LocatieType;
use InloopBundle\Service\LocatieDao;
use InloopBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/locaties")
 * @Template
 */
class LocatiesController extends AbstractController
{
    protected $entityName = 'locatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $filterFormClass = LocatieFilterType::class;
    protected $baseRouteName = 'inloop_locaties_';

    /**
     * @var LocatieDao
     */
    protected $dao;

    protected $accessStrategies = [];

    /**
     * @param LocatieDao $dao
     */
    public function __construct(LocatieDao $dao, $accessStrategies = [])
    {
        $this->dao = $dao;
        $this->accessStrategies = $accessStrategies;
    }

    protected function beforeUpdate($locatie)
    {
        $uow = $this->entityManager->getUnitOfWork();
        $uow->computeChangeSets(); // do not compute changes if inside a listener
        $changeset = $uow->getEntityChangeSet($locatie);

        foreach($this->accessStrategies as $strategy => $intakeLocaties) {

            if(isset($changeset["naam"]) && array_intersect($intakeLocaties, $changeset["naam"]) ){

                throw new UserException(sprintf("Let op, deze locatie heeft speciale functionaliteit in ECD. De naam mag niet aangepast worden."));
            }
        }
    }
}
