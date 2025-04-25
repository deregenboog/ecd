<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Exception\UserException;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use InloopBundle\Service\KlantDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UhkBundle\Entity\Deelnemer;
use UhkBundle\Form\DeelnemerFilterType;
use UhkBundle\Form\DeelnemerType;
use UhkBundle\Security\Permissions;
use UhkBundle\Service\DeelnemerDaoInterface;
use UhkBundle\Service\VerslagDaoInterface;
use UhkBundle\Service\ProjectDaoInterface;

/**
 * @Route("/deelnemers")
 *
 * @Template
 */
class DeelnemersController extends AbstractController
{
    protected $entityName = 'deelnemer';
    protected $entityClass = Deelnemer::class;
    protected $formClass = DeelnemerType::class;
    protected $filterFormClass = DeelnemerFilterType::class;
    protected $searchFilterTypeClass = KlantFilterType::class;
    protected $searchEntity = Klant::class;
    protected $baseRouteName = 'uhk_deelnemers_';
    protected $forceRedirect = true;
    //    protected $disabledActions = ['delete'];


    /**
     * @var DeelnemerDaoInterface
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     */
    protected $klantDao;

    /**
     * @var VerslagDaoInterface
     */
    protected $verslagDao;

    /**
     * @var ProjectDaoInterface
     */
    protected $projectDao;

    public function __construct(DeelnemerDaoInterface $dao, KlantDaoInterface $klantDao, VerslagDaoInterface $verslagDao, ProjectDaoInterface $projectDao)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->searchDao = $klantDao;
        $this->verslagDao = $verslagDao;
        $this->projectDao = $projectDao;
    }



    /**
     * @Route("/{id}/open")
     *
     * @Template
     */
    public function openAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        //        $this->denyAccessUnlessGanted(Permissions::ACCESS, $entity);
        $entity->setActief(true);

        $this->dao->update($entity);
        $this->addFlash('info', 'Deelnemer weer actief gemaakt.');

        return $this->redirectToView($entity);
    }

    /**
     * @Route("/{id}/deleteVerslag/{verslagId}")
     *
     * @Template
     */
    public function deleteVerslagAction(Request $request, $id, $verslagId)
    {
        $entity = $this->dao->find($id);

        if ($entity->getMedewerker() != $this->getMedewerker() && !$this->isGranted('ROLE_UHK_BEHEER')) {
            throw new AccessDeniedException('Mag alleen verslagen verwijderen als je een beheerder bent of het over een eigen verslag gaat.');
        }
        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $viewUrl = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId(), '_fragment' => 'verslagen']);

                $verslag = $this->verslagDao->find($verslagId);
                $this->verslagDao->delete($verslag);

                $this->addFlash('success', 'Verslag is verwijderd.');

                return $this->redirect($viewUrl);
            } else {
                if ($url = $request->get('redirect')) {
                    return $this->redirect($url);
                }

                return $this->redirectToView($entity);
            }
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id){
        $this->entityManager->getFilters()->enable('incidenten')
            ->setParameter('discr', 'uhk')
        ;
        return parent::viewAction($request, $id);
    }

}
