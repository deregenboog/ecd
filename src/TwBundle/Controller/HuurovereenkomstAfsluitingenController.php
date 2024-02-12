<?php

namespace TwBundle\Controller;

use TwBundle\Entity\HuurovereenkomstAfsluiting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\HuurovereenkomstAfsluitingDaoInterface;

/**
 * @Route("/admin/huurovereenkomstafsluitingen")
 * @Template
 */
class HuurovereenkomstAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen koppelingen';

    /**
     * @var HuurovereenkomstAfsluitingDaoInterface
     */
    protected $dao;

    protected $entityClass = HuurovereenkomstAfsluiting::class;

    protected $entityName = 'Afsluiting koppeling';

    protected $indexRouteName = 'tw_huurovereenkomstafsluitingen_index';

    public function __construct(HuurovereenkomstAfsluitingDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
