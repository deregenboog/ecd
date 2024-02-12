<?php

namespace TwBundle\Controller;

use TwBundle\Entity\HuurovereenkomstAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\HuurovereenkomstAfsluitingDao;

/**
 * @Route("/admin/huurovereenkomstafsluitingen")
 * @Template
 */
class HuurovereenkomstAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen koppelingen';

    /**
     * @var HuurovereenkomstAfsluitingDao
     */
    protected $dao;

    protected $entityClass = HuurovereenkomstAfsluiting::class;

    protected $entityName = 'Afsluiting koppeling';

    protected $indexRouteName = 'tw_huurovereenkomstafsluitingen_index';

    /**
     * @param HuurovereenkomstAfsluitingDao $dao
     */
    public function __construct(HuurovereenkomstAfsluitingDao $dao)
    {
        $this->dao = $dao;
    }


}
