<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Form\TrajectFilterType;
use DagbestedingBundle\Form\TrajectType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/trajecten")
 */
class TrajectenController extends AbstractController
{
    protected $title = 'Trajecten';
    protected $entityName = 'Traject';
    protected $entityClass = Traject::class;
    protected $formClass = TrajectType::class;
    protected $filterFormClass = TrajectFilterType::class;
    protected $baseRouteName = 'dagbesteding_trajecten_';

    /**
     * @var DeelnemerDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.traject")
     */
    protected $dao;

    /**
     * @Route("/add/{deelnemer}")
     */
    public function addAction(Request $request, Deelnemer $deelnemer)
    {
        $entity = new $this->entityClass();
        $entity->setDeelnemer($deelnemer);

        $form = $this->createForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($entity);
                $this->addFlash('success', $this->entityName.' is opgeslagen.');
            } catch (\Exception $e) {
                var_dump($e);
                die;
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            var_dump($entity);
            die;

            return $this->redirectToView($entity);
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
