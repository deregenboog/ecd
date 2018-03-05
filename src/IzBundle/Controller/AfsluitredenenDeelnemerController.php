<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\Afsluiting;
use IzBundle\Service\AfsluitingDaoInterface;
use IzBundle\Form\AfsluitingType;

/**
 * @Route("/admin/afsluitredenendeelnemer")
 */
class AfsluitredenenDeelnemerController extends AbstractController
{
    protected $title = 'Afsluitredenen deelnemers';
    protected $entityName = 'afsluitreden deelnemer';
    protected $entityClass = Afsluiting::class;
    protected $formClass = AfsluitingType::class;
    protected $baseRouteName = 'iz_afsluitredenendeelnemer_';

    /**
     * @var AfsluitingDaoInterface
     *
     * @DI\Inject("IzBundle\Service\AfsluitingDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
