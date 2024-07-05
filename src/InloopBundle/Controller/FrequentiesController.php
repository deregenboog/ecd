<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Frequentie;
use InloopBundle\Form\FrequentieType;
use InloopBundle\Service\FrequentieDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/frequenties")
 *
 * @Template
 */
class FrequentiesController extends AbstractController
{
    protected $title = 'Verslavingsfrequenties';
    protected $entityName = 'frequentie';
    protected $entityClass = Frequentie::class;
    protected $formClass = FrequentieType::class;
    protected $baseRouteName = 'inloop_frequenties_';

    /**
     * @var FrequentieDaoInterface
     */
    protected $dao;

    public function __construct(FrequentieDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
