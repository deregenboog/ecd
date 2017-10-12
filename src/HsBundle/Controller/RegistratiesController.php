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
                $this->addFlash('danger', 'Er is een fout opgetreden.');
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

//     /**
//      * @Route("/add/{klus}/{arbeider}")
//      * @ParamConverter()
//      */
//     public function addAction(Klus $klus, Arbeider $arbeider = null)
//     {
//         $entity = new Registratie($klus, $arbeider);
//         $form = $this->createForm(RegistratieType::class, $entity);
//         $form->handleRequest($this->getRequest());
//         if ($form->isSubmitted() && $form->isValid()) {
//             $this->dao->create($entity);

//             return $this->redirectToView($entity);
//         }

//         return [
//             'klus' => $klus,
//             'form' => $form->createView(),
//         ];
//     }

//     /**
//      * @Route("/{id}/edit")
//      */
//     public function editAction($id)
//     {
//         $entity = $this->dao->find($id);
//         $form = $this->createForm(RegistratieType::class, $entity);
//         $form->handleRequest($this->getRequest());
//         if ($form->isSubmitted() && $form->isValid()) {
//             $this->dao->update($entity);

//             return $this->redirectToView($entity);
//         }

//         return ['form' => $form->createView()];
//     }
}
