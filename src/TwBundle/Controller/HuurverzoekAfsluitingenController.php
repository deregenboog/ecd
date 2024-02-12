<?php

namespace TwBundle\Controller;

use TwBundle\Entity\HuurverzoekAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\HuurverzoekAfsluitingDao;

/**
 * @Route("/admin/huurverzoekafsluitingen")
 * @Template
 */
class HuurverzoekAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huurverzoeken';

    /**
     * @var HuurverzoekAfsluitingDao
     */
    protected $dao;

    protected $entityClass = HuurverzoekAfsluiting::class;

    protected $entityName = 'Afsluiting huurverzoek';

    protected $indexRouteName = 'tw_huurverzoekafsluitingen_index';

    /**
     * @param HuurverzoekAfsluitingDao $dao
     */
    public function __construct(HuurverzoekAfsluitingDao $dao)
    {
        $this->dao = $dao;
    }
}
