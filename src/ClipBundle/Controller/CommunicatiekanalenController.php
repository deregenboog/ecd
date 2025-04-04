<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Communicatiekanaal;
use ClipBundle\Form\CommunicatiekanaalType;
use ClipBundle\Service\CommunicatiekanaalDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/communicatiekanalen")
 *
 * @Template
 */
class CommunicatiekanalenController extends AbstractController
{
    protected $title = 'Contacttype';
    protected $entityName = 'contacttype';
    protected $entityClass = Communicatiekanaal::class;
    protected $formClass = CommunicatiekanaalType::class;
    protected $baseRouteName = 'clip_communicatiekanalen_';

    /**
     * @var CommunicatiekanaalDaoInterface
     */
    protected $dao;

    public function __construct(CommunicatiekanaalDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
