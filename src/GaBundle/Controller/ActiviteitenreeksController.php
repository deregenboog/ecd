<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use GaBundle\Entity\Activiteit;
use GaBundle\Form\ActiviteitenReeksModel;
use GaBundle\Form\ActiviteitenReeksType;
use GaBundle\Service\ActiviteitDaoInterface;
use GaBundle\Service\ActiviteitenreeksGenerator;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/activiteitenreeks")
 * @Template
 */
class ActiviteitenreeksController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $title = 'Reeks activiteiten';
    protected $entityName = 'reeks activiteiten';
    protected $entityClass = Activiteit::class;
    protected $formClass = ActiviteitenReeksType::class;
    protected $baseRouteName = 'ga_activiteiten_';

    /**
     * @var ActiviteitDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(ActiviteitDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        [$parentEntity, $this->parentDao] = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
        }

        $groep = isset($request->request->get("activiteiten_reeks")['activiteit']['groep']);
        if ($groep) {
            foreach ($this->entities as $entity) {
                if ($entity['key'] == 'groep') {
                    $parentEntity = $entity['dao']->find($request->get("activiteiten_reeks")['activiteit']['groep']);
                }
            }
        }


        $entity = new Activiteit();
        $entity->setGroep($parentEntity);
        $form = $this->getForm($this->formClass, new ActiviteitenReeksModel($entity), [
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $activiteiten = ActiviteitenreeksGenerator::generate($form->getData());
                foreach ($activiteiten as $activiteit) {
                    $activiteit->setGroep($parentEntity);
                }
                $this->dao->createBatch($activiteiten);
                $this->addFlash('success', (is_array($activiteiten) || $activiteiten instanceof \Countable ? count($activiteiten) : 0).' activiteiten zijn toegevoegd.');
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            }  catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            if ($parentEntity) {
                return $this->redirectToView($parentEntity);
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'parent_entity' => $parentEntity,
            'form' => $form->createView(),
        ];
    }
}
