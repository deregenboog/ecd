<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekBundle\Entity\Groep;
use OekBundle\Form\GroepType;
use OekBundle\Service\GroepDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/groepen")
 */
class GroepenController extends AbstractController
{
    protected $title = 'Groepen';
    protected $entityName = 'groep';
    protected $entityClass = Groep::class;
    protected $formClass = GroepType::class;
    protected $baseRouteName = 'oek_groepen_';

    /**
     * @var GroepDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("OekBundle\Service\GroepDao");
    
        return $previous;
    }
}
