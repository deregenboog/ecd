<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekBundle\Entity\VerwijzingNaar;
use OekBundle\Form\VerwijzingType;
use OekBundle\Service\VerwijzingNaarDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/verwijzingen_naar")
 */
class VerwijzingenNaarController extends AbstractController
{
    protected $title = 'Verwijzingen naar';
    protected $entityName = 'verwijzing naar';
    protected $entityClass = VerwijzingNaar::class;
    protected $formClass = VerwijzingType::class;
    protected $baseRouteName = 'oek_verwijzingennaar_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var VerwijzingNaarDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("OekBundle\Service\VerwijzingNaarDao");
    
        return $previous;
    }
}
