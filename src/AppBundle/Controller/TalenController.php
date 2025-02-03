<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Taal;
use AppBundle\Form\TaalFilterType;
use AppBundle\Form\TaalType;
use AppBundle\Service\TaalDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/talen")
 *
 * @Template
 *
 * @IsGranted("ROLE_ADMIN")
 */
class TalenController extends AbstractController
{
    protected $entityName = 'taal';
    protected $entityClass = Taal::class;
    protected $formClass = TaalType::class;
    protected $filterFormClass = TaalFilterType::class;
    protected $baseRouteName = 'app_talen_';
    protected $disabledActions = ['view'];

    /**
     * @var TaalDaoInterface
     */
    protected $dao;

    public function __construct(TaalDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
