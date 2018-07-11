<?php

namespace GaBundle\Controller;

use GaBundle\Entity\KlantLidmaatschap;
use GaBundle\Entity\Lidmaatschap;
use GaBundle\Form\KlantLidmaatschapCloseType;
use GaBundle\Form\KlantLidmaatschapReopenType;
use GaBundle\Form\KlantLidmaatschapType;
use GaBundle\Service\LidmaatschapDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klantlidmaatschappen")
 */
class KlantlidmaatschappenController extends LidmaatschappenController
{
    protected $entityClass = KlantLidmaatschap::class;
    protected $formClass = KlantLidmaatschapType::class;
    protected $baseRouteName = 'ga_klantlidmaatschappen_';

    /**
     * @var LidmaatschapDaoInterface
     *
     * @DI\Inject("GaBundle\Service\KlantlidmaatschapDao")
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
        $this->formClass = KlantLidmaatschapReopenType::class;

        $entity = $this->dao->find($id);
        $entity->reopen();

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $this->formClass = KlantLidmaatschapCloseType::class;

        $entity = $this->dao->find($id);

        return $this->processForm($request, $entity);
    }
}
