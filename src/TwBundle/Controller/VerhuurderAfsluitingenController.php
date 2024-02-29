<?php

namespace TwBundle\Controller;

use TwBundle\Entity\VerhuurderAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\VerhuurderAfsluitingDaoInterface;

/**
 * @Route("/admin/verhuurderafsluitingen")
 * @Template
 */
class VerhuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen verhuurders';

    /**
     * @var AfsluitingDaoInterface
     */
    protected $dao;

    protected $entityClass = VerhuurderAfsluiting::class;

    protected $entityName = 'Afsluiting verhuurder';

    protected $indexRouteName = 'tw_verhuurderafsluitingen_index';

    public function __construct(AfsluitingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
