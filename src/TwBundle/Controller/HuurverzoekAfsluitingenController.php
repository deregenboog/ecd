<?php

namespace TwBundle\Controller;

use TwBundle\Entity\HuurverzoekAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\HuurverzoekAfsluitingDaoInterface;

/**
 * @Route("/admin/huurverzoekafsluitingen")
 * @Template
 */
class HuurverzoekAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huurverzoeken';

    /**
     * @var AfsluitingDaoInterface
     */
    protected $dao;

    protected $entityClass = HuurverzoekAfsluiting::class;

    protected $entityName = 'Afsluiting huurverzoek';

    protected $indexRouteName = 'tw_huurverzoekafsluitingen_index';

    public function __construct(AfsluitingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
