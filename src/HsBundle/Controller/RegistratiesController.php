<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Registratie;
use HsBundle\Entity\Klus;
use HsBundle\Form\RegistratieType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use HsBundle\Service\RegistratieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use HsBundle\Entity\Arbeider;
use AppBundle\Controller\AbstractChildController;
use Symfony\Component\HttpFoundation\Request;
use HsBundle\Filter\RegistratieFilter;
use AppBundle\Export\ExportInterface;
use AppBundle\Exception\AppException;

/**
 * @Route("/registraties")
 */
class RegistratiesController extends AbstractChildController
{
    protected $title = 'Urenregistratie';
    protected $entityName = 'urenregistratie';
    protected $entityClass = Registratie::class;
    protected $formClass = RegistratieType::class;
    protected $baseRouteName = 'hs_registraties_';

    /**
     * @var RegistratieDaoInterface
     *
     * @DI\Inject("hs.dao.registratie")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("hs.export.registratie")
     */
    protected $export;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("hs.registratie.entities")
     */
    protected $entities;

    /**
     * @Route("/werkbon/{arbeider}")
     * @ParamConverter
     */
    public function werkbonAction(Request $request, Arbeider $arbeider)
    {
        $filter = new RegistratieFilter();
        $filter->arbeider = $arbeider;

        return $this->download($filter);
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        list($parentEntity) = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
        }

        if ($parentEntity instanceof Klus) {
            $entity = new Registratie($parentEntity);
        } elseif ($parentEntity instanceof Arbeider) {
            $entity = new Registratie(null, $parentEntity);
        }

        $form = $this->createForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToView($parentEntity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }
}
