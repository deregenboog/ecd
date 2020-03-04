<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\ContactOntstaan;
use IzBundle\Form\ContactOntstaanType;
use IzBundle\Service\ContactOntstaanDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/contactontstaanopties")
 */
class ContactOntstaanOptiesController extends AbstractController
{
    protected $title = 'Contact ontstaan-opties';
    protected $entityName = 'contact onstaan-optie';
    protected $entityClass = ContactOntstaan::class;
    protected $formClass = ContactOntstaanType::class;
    protected $baseRouteName = 'iz_contactontstaanopties_';

    /**
     * @var ContactOntstaanDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("IzBundle\Service\ContactOntstaanDao");
    
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
