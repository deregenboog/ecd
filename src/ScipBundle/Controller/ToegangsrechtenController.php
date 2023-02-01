<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use ScipBundle\Entity\Toegangsrecht;
use ScipBundle\Form\ToegangsrechtFilterType;
use ScipBundle\Form\ToegangsrechtType;
use ScipBundle\Service\ToegangsrechtDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/toegangsrechten")
 * @Template
 */
class ToegangsrechtenController extends AbstractController
{
    protected $entityName = 'toegangsrecht';
    protected $entityClass = Toegangsrecht::class;
    protected $formClass = ToegangsrechtType::class;
    protected $filterFormClass = ToegangsrechtFilterType::class;
    protected $baseRouteName = 'scip_toegangsrechten_';
    protected $forceRedirect = true;
    protected $disabledActions = ['deleted'];

    /**
     * @var ToegangsrechtDaoInterface
     *
     * @DI\Inject("ScipBundle\Service\ToegangsrechtDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
