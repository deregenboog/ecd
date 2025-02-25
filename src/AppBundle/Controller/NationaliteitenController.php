<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Nationaliteit;
use AppBundle\Form\NationaliteitFilterType;
use AppBundle\Form\NationaliteitType;
use AppBundle\Service\NationaliteitDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/nationaliteiten")
 *
 * @Template
 *
 * @IsGranted("ROLE_ADMIN")
 */
class NationaliteitenController extends AbstractController
{
    protected $entityName = 'nationaliteit';
    protected $entityClass = Nationaliteit::class;
    protected $formClass = NationaliteitType::class;
    protected $filterFormClass = NationaliteitFilterType::class;
    protected $baseRouteName = 'app_nationaliteiten_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var NationaliteitDaoInterface
     */
    protected $dao;

    public function __construct(NationaliteitDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
