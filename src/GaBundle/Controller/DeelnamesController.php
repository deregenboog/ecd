<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use GaBundle\Entity\Activiteit;
use GaBundle\Entity\Deelname;
use GaBundle\Entity\Klantdossier;
use GaBundle\Entity\Vrijwilligerdossier;
use GaBundle\Form\DeelnameType;
use GaBundle\Service\ActiviteitDaoInterface;
use GaBundle\Service\DeelnameDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deelnames")
 * @Template
 */
class DeelnamesController extends AbstractChildController
{
    protected $title = 'Deelnames';
    protected $entityName = 'deelname';
    protected $entityClass = Deelname::class;
    protected $formClass = DeelnameType::class;
    protected $addMethod = 'addDeelname';
    protected $baseRouteName = 'ga_deelnames_';

    /**
     * @var DeelnameDaoInterface
     */
    protected $dao;

    /**
     * @var ActiviteitDaoInterface
     */
    protected $activiteitDao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->dao = $this->get("GaBundle\Service\DeelnameDao");
        $this->activiteitDao = $this->get("GaBundle\Service\ActiviteitDao");
        $this->entities = $this->get("ga.deelname.entities");
    
        return $container;
    }

    /**
     * Alle leden van groep toevoegen aan activiteit.
     *
     * @Route("/{activiteit}/addall")
     */
    public function addAllAction(Request $request, $activiteit)
    {
        /* @var $activiteit Activiteit */
        $activiteit = $this->activiteitDao->find($activiteit);
        $dossierClass = $this->getDossierClass($request);

        if ($activiteit->getGroep()) {
            foreach ($activiteit->getGroep()->getLidmaatschappen() as $lidmaatschap) {
                if ($lidmaatschap->getDossier() instanceof $dossierClass
                    && $lidmaatschap->isActief()
                    && !$activiteit->getDossiers()->contains($lidmaatschap->getDossier())
                ) {
                    $activiteit->addDeelname(new Deelname($activiteit, $lidmaatschap->getDossier()));
                }
            }
        }
        $this->activiteitDao->update($activiteit);

        switch ($dossierClass) {
            case Klantdossier::class:
                $this->addFlash('success', 'Alle deelnemers zijn toegevoegd.');
                break;
            case Vrijwilligerdossier::class:
                $this->addFlash('success', 'Alle vrijwilliger zijn toegevoegd.');
                break;
            default:
                break;
        }

        return $this->redirectToRoute('ga_activiteiten_view', ['id' => $activiteit->getId()]);
    }

    protected function createEntity($parentEntity = null)
    {
        if ($parentEntity instanceof Activiteit) {
            return new $this->entityClass($parentEntity);
        }

        return new $this->entityClass(null, $parentEntity);
    }

    protected function persistEntity($entity, $parentEntity)
    {
        $this->dao->create($entity);
    }

    protected function getForm($type, $data = null, array $options = [])
    {
        if (DeelnameType::class === $type) {
            $options['dossier_class'] = $this->getDossierClass($this->getRequest());
        }

        return $this->createForm($type, $data, $options);
    }

    private function getDossierClass(Request $request)
    {
        switch ($request->query->get('type')) {
            case 'klant':
                return Klantdossier::class;
            case 'vrijwilliger':
                return Vrijwilligerdossier::class;
            default:
                break;
        }
    }
}
