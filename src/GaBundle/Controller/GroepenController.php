<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\AbstractExport;
use GaBundle\Entity\Groep;
use GaBundle\Form\GroepFilterType;
use GaBundle\Form\GroepType;
use GaBundle\Service\GroepDao;
use GaBundle\Service\KlantLidmaatschapDao;
use GaBundle\Service\LidmaatschapDao;
use GaBundle\Service\VrijwilligerLidmaatschapDao;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/groepen")
 * @Template
 */
class GroepenController extends AbstractController
{
    protected $title = 'Groepen';
    protected $entityName = 'groep';
    protected $entityClass = Groep::class;
    protected $formClass = GroepType::class;
    protected $filterFormClass = GroepFilterType::class;
    protected $baseRouteName = 'ga_groepen_';

    /**
     * @var GroepDao 
     */
    protected $dao;

    /**
     * @var LidmaatschapDao 
     */
    protected $klantLidmaatschapDao;

    /**
     * @var LidmaatschapDao 
     */
    protected $vrijwilligerLidmaatschapDao;

    /**
     * @var AbstractExport
     */
    protected $export;

    /**
     * @param GroepDao $dao
     * @param KlantLidmaatschapDao $klantLidmaatschapDao
     * @param VrijwilligerLidmaatschapDao $vrijwilligerLidmaatschapDao
     * @param AbstractExport $export
     */
    public function __construct(GroepDao $dao, KlantLidmaatschapDao $klantLidmaatschapDao, VrijwilligerLidmaatschapDao $vrijwilligerLidmaatschapDao, AbstractExport $export)
    {
        $this->dao = $dao;
        $this->klantLidmaatschapDao = $klantLidmaatschapDao;
        $this->vrijwilligerLidmaatschapDao = $vrijwilligerLidmaatschapDao;
        $this->export = $export;
    }


    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->get('type')) {
            $this->forceRedirect = true;
            $this->entityClass = str_replace('Groep', Groep::class, $request->get('type'));

            $entity = $this->createEntity();
            if ($entity instanceof Groep) {
                return $this->processForm($request, $entity);
            }
        }

        return [];
    }

    protected function addParams($entity, Request $request)
    {
        if (!$entity->getId()) {
            return [];
        }

        assert($entity instanceof Groep);

        return [
            'klantlidmaatschappen' => $this->klantLidmaatschapDao->findByGroep($entity, $request->get('page_klant', 1)),
            'vrijwilligerlidmaatschappen' => $this->vrijwilligerLidmaatschapDao->findByGroep($entity, $request->get('page_vrijwilliger', 1)),
        ];
    }
}
