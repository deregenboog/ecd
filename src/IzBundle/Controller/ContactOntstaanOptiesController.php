<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\ContactOntstaan;
use IzBundle\Form\ContactOntstaanType;

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
     *
     * @DI\Inject("IzBundle\Service\ContactOntstaanDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
