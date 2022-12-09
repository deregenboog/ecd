<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\ContactOntstaan;
use IzBundle\Form\ContactOntstaanType;
use IzBundle\Service\ContactOntstaanDao;
use IzBundle\Service\ContactOntstaanDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
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
     * @var ContactOntstaanDao
     */
    protected $dao;

    /**
     * @param ContactOntstaanDao $dao
     */
    public function __construct(ContactOntstaanDao $dao)
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
