<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Form\Model\AppDateRangeModel;
use GaBundle\Entity\Activiteit;
use GaBundle\Filter\ActiviteitFilter;
use GaBundle\Form\ActiviteitFilterType;
use GaBundle\Form\ActiviteitType;
use GaBundle\Service\ActiviteitDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/activiteiten")
 */
class ActiviteitenController extends AbstractChildController
{
    protected $title = 'Activiteiten';
    protected $entityName = 'activiteit';
    protected $entityClass = Activiteit::class;
    protected $formClass = ActiviteitType::class;
    protected $filterFormClass = ActiviteitFilterType::class;
    protected $addMethod = 'addActiviteit';
    protected $baseRouteName = 'ga_activiteiten_';

    /**
     * @var ActiviteitDaoInterface
     *
     * @DI\Inject("GaBundle\Service\ActiviteitDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("ga.activiteit.entities")
     */
    protected $entities;

    /**
     * @Route("/calendar")
     * @Template
     */
    public function calendarAction()
    {
        return [];
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
