<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\AbstractExport;
use GaBundle\Entity\Groep;
use GaBundle\Form\GroepFilterType;
use GaBundle\Form\GroepType;
use GaBundle\Service\GroepDaoInterface;
use GaBundle\Service\KlantLidmaatschapDaoInterface;
use GaBundle\Service\LidmaatschapDaoInterface;
use GaBundle\Service\VrijwilligerLidmaatschapDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/groepen")
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

    public function __construct(GroepDaoInterface $dao, LidmaatschapDaoInterface $klantLidmaatschapDao, LidmaatschapDaoInterface $vrijwilligerLidmaatschapDao, AbstractExport $export)
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

        $groupTypes = $this->getGroupTypes();
        return ['groupTypes'=>$groupTypes];
    }

    /**
     * Retrieves an array of group types.
     *
     * This method retrieves the group types available based on the entity class used
     * in the current context. It uses the EntityManager instance to get the ClassMetadata
     * for the entity class, then iterates through the discriminator map to build an array
     * of group types. The array keys represent the short names of the entity classes, while
     * the values represent the corresponding discriminator values.
     *
     * @return array An associative array of group types, where the keys are the short names
     *               of entity classes and the values are the corresponding discriminator values.
     */
    private function getGroupTypes(): array
    {
        $classMetaData = $this->entityManager->getClassMetadata($this->entityClass);

        $outputArray = [];

        foreach ($classMetaData->discriminatorMap as $key => $className) {
            $reflectionClass = new \ReflectionClass($className);
            $outputArray[$reflectionClass->getShortName()] = $key;
        }

        return $outputArray;
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
