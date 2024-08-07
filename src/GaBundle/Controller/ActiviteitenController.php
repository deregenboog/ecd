<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use AppBundle\Form\Model\AppDateRangeModel;
use GaBundle\Entity\Activiteit;
use GaBundle\Filter\ActiviteitFilter;
use GaBundle\Form\ActiviteitCancelType;
use GaBundle\Form\ActiviteitFilterType;
use GaBundle\Form\ActiviteitType;
use GaBundle\Form\DeelnamesType;
use GaBundle\Service\ActiviteitDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/activiteiten")
 *
 * @Template
 */
class ActiviteitenController extends AbstractChildController
{
    protected $entityName = 'activiteit';
    protected $entityClass = Activiteit::class;
    protected $formClass = ActiviteitType::class;
    protected $filterFormClass = ActiviteitFilterType::class;
    protected $addMethod = 'addActiviteit';
    protected $baseRouteName = 'ga_activiteiten_';

    /**
     * @var ActiviteitDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var AbstractExport
     */
    protected $export;

    public function __construct(ActiviteitDaoInterface $dao, \ArrayObject $entities, AbstractExport $export)
    {
        $this->dao = $dao;
        $this->entities = $entities;
        $this->export = $export;
    }

    /**
     * @Route("/calendar")
     *
     * @Template
     */
    public function calendarAction()
    {
        return [];
    }

    /**
     * @Route("/{id}/cancel")
     */
    public function cancelAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $this->formClass = ActiviteitCancelType::class;

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/editdeelnames")
     */
    public function editDeelnamesAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $this->formClass = DeelnamesType::class;

        return $this->processForm($request, $entity);
    }

    /**
     * @Route(".json")
     */
    public function jsonAction(Request $request)
    {
        $filter = new ActiviteitFilter();
        $filter->datum = new AppDateRangeModel();
        if ($request->query->has('start')) {
            $filter->datum->setStart(new \DateTime($request->query->get('start')));
        }
        if ($request->query->has('end')) {
            $filter->datum->setEnd(new \DateTime($request->query->get('end')));
        }

        $activiteiten = $this->dao->findAll(null, $filter);

        $map = function (Activiteit $activiteit) {
            return [
                'url' => $this->generateUrl('ga_activiteiten_view', ['id' => $activiteit->getId()]),
                'title' => $activiteit->getNaam(),
                'start' => $activiteit->getDatum()->format('Y-m-d\TH:i'),
            ];
        };

        return new JsonResponse(array_map($map, $activiteiten));
    }
}
