<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\Verhuurder;
use TwBundle\Form\VerhuurderCloseType;
use TwBundle\Form\VerhuurderFilterType;
use TwBundle\Form\VerhuurderSelectType;
use TwBundle\Form\VerhuurderType;
use TwBundle\Service\VerhuurderDaoInterface;

/**
 * @Route("/verhuurders")
 *
 * @Template
 */
class VerhuurdersController extends AbstractController
{
    public $title = 'Verhuurders';
    public $entityClass = Verhuurder::class;
    protected $entityName = 'verhuurder';
    protected $formClass = VerhuurderType::class;
    protected $filterFormClass = VerhuurderFilterType::class;
    protected $baseRouteName = 'tw_verhuurders_';
    protected $addMethod = 'addVerhuurder';
    protected $searchFilterTypeClass = KlantFilterType::class;
    protected $searchEntity = AppKlant::class;
    protected $searchEntityName = 'appKlant';

    /**
     * @var VerhuurderDaoInterface
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     */
    protected $searchDaoInterface;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(VerhuurderDaoInterface $dao, KlantDaoInterface $searchDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->searchDao = $searchDao;
        $this->export = $export;
    }

    protected function getDownloadFilename()
    {
        $filename = sprintf('tw-verhuurders-%s.xlsx', (new \DateTime())->format('d-m-Y'));

        return $filename;
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $verhuurder = $this->getEntityManager()->find(Verhuurder::class, $id);

        return ['verhuurder' => $verhuurder];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->getForm(VerhuurderType::class, $verhuurder);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Verhuurder is opgeslagen.');

                return $this->redirectToRoute('tw_verhuurders_view', ['id' => $verhuurder->getId()]);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        return [
            'verhuurder' => $verhuurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getFilters()->enable('active');
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->getForm(VerhuurderCloseType::class, $verhuurder);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Verhuurder is afgesloten.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('tw_verhuurders_view', ['id' => $verhuurder->getId()]);
        }

        return [
            'verhuurder' => $verhuurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopen($id)
    {
        $entityManager = $this->getEntityManager();
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $verhuurder->reopen($this->getUser());
                    $entityManager->flush();

                    $this->addFlash('success', 'Klant is heropend.');
                } catch (UserException $e) {
                    //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $message = $e->getMessage();
                    $this->addFlash('danger', $message);
                    //                return $this->redirectToRoute('app_klanten_index');
                } catch (\Exception $e) {
                    $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
                }
            }

            return $this->redirectToRoute('tw_verhuurders_view', ['id' => $verhuurder->getId()]);
        }

        return [
            'verhuurder' => $verhuurder,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $verhuurder = $entityManager->find(Verhuurder::class, $id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($verhuurder);
                $entityManager->flush();

                $this->addFlash('success', 'Verhuurder is verwijderd.');

                return $this->redirectToRoute('tw_verhuurders_index');
            } else {
                return $this->redirectToRoute('tw_verhuurders_view', ['id' => $verhuurder->getId()]);
            }
        }

        return [
            'verhuurder' => $verhuurder,
            'form' => $form->createView(),
        ];
    }
}
