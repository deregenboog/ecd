<?php

namespace TwBundle\Controller;

use IzBundle\Service\AfsluitingDao;
use TwBundle\Entity\HuuraanbodAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huuraanbodafsluitingen")
 * @Template
 */
class HuuraanbodAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huuraanbiedingen';

    /**
     * @var AfsluitingDao
     */
    protected $dao;

    protected $entityClass = HuuraanbodAfsluiting::class;

    protected $entityName = 'Afsluiting huuraanbod';

    protected $indexRouteName = 'tw_huuraanbodafsluitingen_index';

    /**
     * @param AfsluitingDao $dao
     */
    public function __construct(AfsluitingDao $dao)
    {
        $this->dao = $dao;
    }


}
