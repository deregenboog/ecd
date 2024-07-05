<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractController;
use HsBundle\Entity\Activiteit;
use HsBundle\Form\ActiviteitType;
use HsBundle\Service\ActiviteitDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/activiteiten")
 *
 * @Template
 */
class ActiviteitenController extends AbstractController
{
    protected $entityName = 'activiteit';
    protected $entityClass = Activiteit::class;
    protected $formClass = ActiviteitType::class;
    protected $baseRouteName = 'hs_activiteiten_';

    /**
     * @var ActiviteitDaoInterface
     */
    protected $dao;

    public function __construct(ActiviteitDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
