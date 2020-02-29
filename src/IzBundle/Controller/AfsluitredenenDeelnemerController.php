<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Afsluiting;
use IzBundle\Form\AfsluitingType;
use IzBundle\Service\AfsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/afsluitredenendeelnemer")
 */
class AfsluitredenenDeelnemerController extends AbstractController
{
    protected $title = 'Afsluitredenen deelnemers/vrijwilligers';
    protected $entityName = 'afsluitreden deelnemer/vrijwilliger';
    protected $entityClass = Afsluiting::class;
    protected $formClass = AfsluitingType::class;
    protected $baseRouteName = 'iz_afsluitredenendeelnemer_';

    /**
     * @var AfsluitingDaoInterface
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("IzBundle\Service\AfsluitingDao");
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
