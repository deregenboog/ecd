<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use GaBundle\Entity\Klantdossier;
use GaBundle\Entity\Lidmaatschap;
use GaBundle\Entity\Vrijwilligerdossier;
use GaBundle\Form\LidmaatschapCloseType;
use GaBundle\Form\LidmaatschapReopenType;
use GaBundle\Form\LidmaatschapType;
use GaBundle\Service\LidmaatschapDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lidmaatschappen")
 * @Template
 */
class LidmaatschappenController extends AbstractChildController
{
    protected $entityName = 'lidmaatschap';
    protected $entityClass = Lidmaatschap::class;
    protected $formClass = LidmaatschapType::class;
    protected $addMethod = 'addLidmaatschap';
    protected $baseRouteName = 'ga_lidmaatschappen_';
    protected $disabledActions = ['delete'];

    /**
     * @var LidmaatschapDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(LidmaatschapDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopenAction(Request $request, $id)
    {
        $this->formClass = LidmaatschapReopenType::class;

        $entity = $this->dao->find($id);
        $entity->reopen();

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $this->formClass = LidmaatschapCloseType::class;

        $entity = $this->dao->find($id);
        $entity->close();

        return $this->processForm($request, $entity);
    }

    protected function getForm($type, $data = null, array $options = [])
    {
        if (LidmaatschapType::class === $type) {
            $options['dossier_class'] = $this->getDossierClass($this->getRequest());
        }

        return $this->createForm($type, $data, $options);
    }

    private function getDossierClass(Request $request)
    {
        switch ($request->query->get('type')) {
            case 'klant':
                return Klantdossier::class;
            case 'vrijwilliger':
                return Vrijwilligerdossier::class;
            default:
                break;
        }
    }
}
