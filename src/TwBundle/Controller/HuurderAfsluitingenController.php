<?php

namespace TwBundle\Controller;

use IzBundle\Service\AfsluitingDao;
use TwBundle\Entity\KlantAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\HuurderAfsluitingDaoInterface;

/**
 * @Route("/admin/huurderafsluitingen")
 * @Template
 */
class HuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen klanten';

    /**
     * @var AfsluitingDaoInterface
     */
    protected $dao;

    protected $entityClass = KlantAfsluiting::class;

    protected $entityName = 'Afsluiting klant';

    protected $indexRouteName = 'tw_huurderafsluitingen_index';

    public function __construct(AfsluitingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
