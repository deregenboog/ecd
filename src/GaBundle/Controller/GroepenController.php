<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\AbstractExport;
use GaBundle\Entity\Groep;
use GaBundle\Form\GroepFilterType;
use GaBundle\Form\GroepType;
use GaBundle\Service\GroepDaoInterface;
use GaBundle\Service\GroupTypeContainer;
use GaBundle\Service\LidmaatschapDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/groepen")
 *
 * @Template
 */
class GroepenController extends AbstractController
{
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

    private GroupTypeContainer $groupTypes;

    public function __construct(
        GroepDaoInterface $dao,
        LidmaatschapDaoInterface $klantLidmaatschapDao,
        LidmaatschapDaoInterface $vrijwilligerLidmaatschapDao,
        AbstractExport $export,
        GroupTypeContainer $groupTypes
    ) {
        $this->dao = $dao;
        $this->klantLidmaatschapDao = $klantLidmaatschapDao;
        $this->vrijwilligerLidmaatschapDao = $vrijwilligerLidmaatschapDao;
        $this->export = $export;
        $this->groupTypes = $groupTypes;
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->get('type')) {
            $this->forceRedirect = true;
            $this->entityClass = $this->groupTypes->getType($request->get('type'))->getClassName();

            $entity = $this->createEntity();
            if ($entity instanceof Groep) {
                return $this->processForm($request, $entity);
            }
        }

        return ['group_types' => $this->groupTypes->getTypeNames()];
    }

    protected function addParams($entity, Request $request): array
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
