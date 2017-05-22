<?php

namespace DagbestedingBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use DagbestedingBundle\Entity\DeelnemerAfsluiting;
use DagbestedingBundle\Service\DeelnemerAfsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Form\DeelnemerAfsluitingType;

/**
 * @Route("/admin/deelnemerafsluitingen")
 */
class DeelnemerAfsluitingenController extends AbstractController
{
    protected $title = 'Afsluitingen deelnemers';
    protected $entityName = 'Afsluiting deelnemer';
    protected $entityClass = DeelnemerAfsluiting::class;
    protected $formClass = DeelnemerAfsluitingType::class;
    protected $baseRouteName = 'dagbesteding_deelnemerafsluitingen_';
    protected $templatePath = 'afsluitingen';

    /**
     * @var DeelnemerAfsluitingDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.deelnemerafsluiting")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }
}
