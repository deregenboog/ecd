<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Succesindicator;
use IzBundle\Form\SuccesindicatorType;
use IzBundle\Service\SuccesindicatorDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/succesindicatoren")
 */
class SuccesindicatorenController extends AbstractController
{
    protected $title = 'Succesindicatoren';
    protected $entityName = 'succesindicator';
    protected $entityClass = Succesindicator::class;
    protected $formClass = SuccesindicatorType::class;
    protected $baseRouteName = 'iz_succesindicatoren_';

    /**
     * @var SuccesindicatorDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("IzBundle\Service\SuccesindicatorDao");
    
        return $previous;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
