<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use HsBundle\Entity\Klant;
use HsBundle\Form\KlantFilterType;
use HsBundle\Form\KlantType;
use HsBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $title = 'Klanten';
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'hs_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("HsBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("hs.export.klant")
     */
    protected $export;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $klant = new Klant();
        $form = $this->getForm(KlantType::class, $klant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($klant);
                $this->addFlash('success', 'Klant is opgeslagen.');

                return $this->redirectToRoute('hs_memos_add', [
                    'klant' => $klant->getId(),
                    'redirect' => $this->generateUrl('hs_klanten_view', ['id' => $klant->getId()]).'#memos',
                ]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'creationForm' => $form->createView(),
        ];
    }
}
