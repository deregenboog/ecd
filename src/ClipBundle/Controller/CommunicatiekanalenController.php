<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ClipBundle\Entity\Communicatiekanaal;
use ClipBundle\Form\CommunicatiekanaalType;
use ClipBundle\Service\CommunicatiekanaalDaoInterface;

/**
 * @Route("/admin/communicatiekanalen")
 */
class CommunicatiekanalenController extends AbstractController
{
    protected $title = 'Locatie';
    protected $entityName = 'locatie';
    protected $entityClass = Communicatiekanaal::class;
    protected $formClass = CommunicatiekanaalType::class;
    protected $baseRouteName = 'clip_communicatiekanalen_';

    /**
     * @var CommunicatiekanaalDaoInterface
     *
     * @DI\Inject("clip.dao.communicatiekanaal")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }
}
