<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\AbstractExport;
use GaBundle\Entity\Groep;
use GaBundle\Form\GroepFilterType;
use GaBundle\Form\GroepType;
use GaBundle\Service\GroepDaoInterface;
use GaBundle\Service\LidmaatschapDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @var GroepDaoInterface
     */
    protected $dao;

    /**
     * @var LidmaatschapDaoInterface
     */
    protected $klantLidmaatschapDao;

    /**
     * @var LidmaatschapDaoInterface
     */
    protected $vrijwilligerLidmaatschapDao;

    /**
     * @var AbstractExport
     */
    protected $export;

    public function setContainer(?\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->dao = $container->get("GaBundle\Service\GroepDao");
        $this->klantLidmaatschapDao = $container->get("GaBundle\Service\KlantLidmaatschapDao");
        $this->vrijwilligerLidmaatschapDao = $container->get("GaBundle\Service\VrijwilligerLidmaatschapDao");
        $this->export = $container->get('ga.export.groepen');
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
