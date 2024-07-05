<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\ContactOntstaan;
use IzBundle\Form\ContactOntstaanType;
use IzBundle\Service\ContactOntstaanDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __construct(ContactOntstaanDaoInterface $dao)
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

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
