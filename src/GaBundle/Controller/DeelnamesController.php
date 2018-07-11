<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use GaBundle\Entity\Activiteit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deelnames")
 */
class DeelnamesController extends AbstractChildController
{
    protected $title = 'Deelnames';
    protected $entityName = 'deelname';
    protected $disabledActions = ['delete'];
    protected $allowEmpty = true;

    /**
     * Alle leden van groep toevoegen aan activiteit.
     *
     * @Route("/{activiteit}/addall")
     */
    public function addAllAction(Request $request, $activiteit)
    {
        /** @var $activiteit Activiteit */
        $activiteit = $this->activiteitDao->find($activiteit);
        $dossierClass = $this->getDossierClass($request);

        if ($activiteit->getGroep()) {
            foreach ($activiteit->getGroep()->getLidmaatschappen() as $lidmaatschap) {
                if ($lidmaatschap->getDossier() instanceof $dossierClass) {
                    if (!$activiteit->getDossiers()->contains($lidmaatschap->getDossier())) {
                        $activiteit->addDeelname(new Deelname($activiteit, $lidmaatschap->getDossier()));
                    }
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

    protected function createEntity($parentEntity)
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
}
