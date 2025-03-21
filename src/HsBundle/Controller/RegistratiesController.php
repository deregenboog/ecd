<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use HsBundle\Entity\Arbeider;
use HsBundle\Entity\FactuurSubjectHelper;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;
use HsBundle\Filter\RegistratieFilter;
use HsBundle\Form\RegistratieFilterType;
use HsBundle\Form\RegistratieType;
use HsBundle\Service\RegistratieDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/registraties")
 *
 * @Template
 */
class RegistratiesController extends AbstractChildController
{
    protected $title = 'Urenregistratie';
    protected $entityName = 'urenregistratie';
    protected $entityClass = Registratie::class;
    protected $formClass = RegistratieType::class;
    protected $filterFormClass = RegistratieFilterType::class;
    protected $baseRouteName = 'hs_registraties_';

    /**
     * @var RegistratieDaoInterface
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(RegistratieDaoInterface $dao, ExportInterface $export, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->export = $export;
        $this->entities = $entities;
    }

    /**
     * @Route("/werkbon/{arbeider}")
     *
     * @ParamConverter
     */
    public function werkbonAction(Request $request, Arbeider $arbeider)
    {
        $filter = new RegistratieFilter();
        $filter->arbeider = $arbeider;

        return $this->download($filter);
    }

    public function beforeCreate($entity): void
    {
        $this->beforeUpdate($entity);
    }

    public function beforeUpdate($entity): void
    {
        $helper = new FactuurSubjectHelper();
        $helper->beforeUpdateEntity($entity, $this->getEntityManager());
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $entity = null;
        [$parentEntity] = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
        }

        if ($parentEntity instanceof Klus) {
            $entity = new Registratie($parentEntity);
        } elseif ($parentEntity instanceof Arbeider) {
            $entity = new Registratie(null, $parentEntity);
        }

        $form = $this->getForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->beforeCreate($entity);
                $this->dao->create($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
