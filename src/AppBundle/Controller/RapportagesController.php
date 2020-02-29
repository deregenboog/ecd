<?php

namespace AppBundle\Controller;

use AppBundle\Export\ExportInterface;
use AppBundle\Form\RapportageType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app/rapportages")
 */
class RapportagesController extends AbstractRapportagesController
{
    protected $formClass = RapportageType::class;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct()
    {
        $this->export = $this->get("app.export.report");
    }
}
