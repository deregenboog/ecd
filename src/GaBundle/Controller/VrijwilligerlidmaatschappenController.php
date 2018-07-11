<?php

namespace GaBundle\Controller;

use GaBundle\Entity\Lidmaatschap;
use GaBundle\Entity\VrijwilligerLidmaatschap;
use GaBundle\Form\VrijwilligerLidmaatschapCloseType;
use GaBundle\Form\VrijwilligerLidmaatschapReopenType;
use GaBundle\Form\VrijwilligerLidmaatschapType;
use GaBundle\Service\LidmaatschapDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vrijwilligerlidmaatschappen")
 */
class VrijwilligerlidmaatschappenController extends LidmaatschappenController
{
    protected $entityClass = VrijwilligerLidmaatschap::class;
    protected $formClass = VrijwilligerLidmaatschapType::class;
    protected $baseRouteName = 'ga_vrijwilligerlidmaatschappen_';

    /**
     * @var LidmaatschapDaoInterface
     *
     * @DI\Inject("GaBundle\Service\VrijwilligerlidmaatschapDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("ga.lidmaatschap.entities")
     */
    protected $entities;

    /**
     * @Route("/{id}/reopen")
     */
    public function reopenAction(Request $request, $id)
    {
        $this->formClass = VrijwilligerLidmaatschapReopenType::class;

        $entity = $this->dao->find($id);
        $entity->reopen();

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $this->formClass = VrijwilligerLidmaatschapCloseType::class;

        $entity = $this->dao->find($id);

        return $this->processForm($request, $entity);
    }
}
