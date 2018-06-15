<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use InloopBundle\Entity\Afsluiting;
use InloopBundle\Form\AfsluitingType;
use InloopBundle\Form\KlantFilterType;
use InloopBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/klanten")
 */
class KlantenController extends AbstractController
{
    protected $title = 'Klanten';
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'inloop_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @Route("/close/{id}")
     */
    public function closeAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $afsluiting = new Afsluiting($klant, $this->getMedewerker());

        $form = $this->createForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($afsluiting);
                $entityManager->flush();

                $this->addFlash('success', 'Inloop-dossier is afgesloten');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('inloop_klanten_index');
        }

        return [
            'klant' => $klant,
            'form' => $form->createView(),
        ];
    }
}
