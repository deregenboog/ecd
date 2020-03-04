<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Deelnemerafsluiting;
use DagbestedingBundle\Form\DeelnemerafsluitingType;
use DagbestedingBundle\Service\DeelnemerafsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/deelnemerafsluitingen")
 * @Template
 */
class DeelnemerafsluitingenController extends AbstractController
{
    protected $title = 'Afsluitingen deelnemers';
    protected $entityName = 'Afsluiting deelnemer';
    protected $entityClass = Deelnemerafsluiting::class;
    protected $formClass = DeelnemerafsluitingType::class;
    protected $baseRouteName = 'dagbesteding_deelnemerafsluitingen_';
    protected $templatePath = 'afsluitingen';

    /**
     * @var DeelnemerafsluitingDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("DagbestedingBundle\Service\DeelnemerafsluitingDao");
    
        return $previous;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
