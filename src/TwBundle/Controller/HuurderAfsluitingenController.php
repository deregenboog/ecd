<?php

namespace TwBundle\Controller;

use IzBundle\Service\AfsluitingDao;
use JMS\DiExtraBundle\Annotation as DI;
use TwBundle\Entity\KlantAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\HuurderAfsluitingDao;

/**
 * @Route("/admin/huurderafsluitingen")
 * @Template
 */
class HuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen klanten';

    /**
     * @var HuurderAfsluitingDao
     */
    protected $dao;

    protected $entityClass = KlantAfsluiting::class;

    protected $entityName = 'Afsluiting klant';

    protected $indexRouteName = 'tw_huurderafsluitingen_index';

    /**
     * @param HuurderAfsluitingDao $dao
     */
    public function __construct(HuurderAfsluitingDao $dao)
    {
        $this->dao = $dao;
    }


}
