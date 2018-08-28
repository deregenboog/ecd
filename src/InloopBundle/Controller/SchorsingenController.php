<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Schorsing;
use InloopBundle\Form\SchorsingFilterType;
use InloopBundle\Form\SchorsingType;
use InloopBundle\Service\SchorsingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/schorsingen")
 */
class SchorsingenController extends AbstractController
{
    protected $title = 'Schorsingen';
    protected $entityName = 'schorsing';
    protected $entityClass = Schorsing::class;
    protected $formClass = SchorsingType::class;
    protected $filterFormClass = SchorsingFilterType::class;
    protected $baseRouteName = 'inloop_schorsingen_';

    /**
     * @var SchorsingDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\SchorsingDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        if (!array_key_exists(GROUP_TEAMLEIDERS, $this->userGroups)) {
            $this->addFlash('danger', 'U bent niet bevoegd schorsingen te wijzigen.');

            return $this->redirectToIndex();
        }

        return parent::editAction($request, $id);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        if (!array_key_exists(GROUP_TEAMLEIDERS, $this->userGroups)) {
            $this->addFlash('danger', 'U bent niet bevoegd schorsingen te verwijderen.');

            return $this->redirectToIndex();
        }

        return parent::deleteAction($request, $id);
    }
}
