<?php

namespace ClipBundle\Controller;

use ClipBundle\Service\VraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ClipBundle\Entity\Vraag;
use AppBundle\Export\ExportInterface;

/**
 * @Route("/openstaandevragen")
 */
class OpenstaandeVragenController extends AbstractVragenController
{
    protected $title = 'Openstaande vragen';

    /**
     * @var VraagDaoInterface
     *
     * @DI\Inject("clip.dao.vraag")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.vragen")
     */
    protected $export;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

    protected function createForm($type, $data = null, array $options = [])
    {
        $options['enabled_filters'] = [
            'id',
            'startdatum',
            'soort',
            'behandelaar',
            'client' => ['klant' => ['naam']],
        ];

        return parent::createForm($type, $data, $options);
    }
}
