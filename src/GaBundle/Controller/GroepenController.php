<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\Groep;
use GaBundle\Form\GroepType;
use GaBundle\Service\GroepDaoInterface;
use GaBundle\Service\LidmaatschapDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/groepen")
 */
class GroepenController extends AbstractController
{
    protected $title = 'Groepen';
    protected $entityName = 'groep';
    protected $entityClass = Groep::class;
    protected $formClass = GroepType::class;
    protected $baseRouteName = 'ga_groepen_';

    /**
     * @var GroepDaoInterface
     *
     * @DI\Inject("GaBundle\Service\GroepDao")
     */
    protected $dao;

    /**
     * @var LidmaatschapDaoInterface
     *
     * @DI\Inject("GaBundle\Service\KlantLidmaatschapDao")
     */
    protected $klantLidmaatschapDao;

    /**
     * @var LidmaatschapDaoInterface
     *
     * @DI\Inject("GaBundle\Service\VrijwilligerLidmaatschapDao")
     */
    protected $vrijwilligerLidmaatschapDao;

    protected function addParams($entity, Request $request)
    {
        assert($entity instanceof Groep);

        return [
            'klantlidmaatschappen' => $this->klantLidmaatschapDao->findByGroep($entity, $request->get('page_klant', 1)),
            'vrijwilligerlidmaatschappen' => $this->vrijwilligerLidmaatschapDao->findByGroep($entity, $request->get('page_vrijwilliger', 1)),
        ];
    }
}
