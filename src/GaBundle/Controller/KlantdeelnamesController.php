<?php

namespace GaBundle\Controller;

use GaBundle\Entity\Activiteit;
use GaBundle\Entity\KlantDeelname;
use GaBundle\Form\KlantDeelnameType;
use GaBundle\Service\ActiviteitDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klantdeelnames")
 */
class KlantdeelnamesController extends DeelnamesController
{
    protected $entityClass = KlantDeelname::class;
    protected $formClass = KlantDeelnameType::class;
    protected $baseRouteName = 'ga_klantdeelnames_';

    /**
     * @var KlantDeelnameDaoInterface
     *
     * @DI\Inject("GaBundle\Service\KlantDeelnameDao")
     */
    protected $dao;

    /**
     * @var ActiviteitDaoInterface
     *
     * @DI\Inject("GaBundle\Service\ActiviteitDao")
     */
    protected $activiteitDao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("ga.deelname.entities")
     */
    protected $entities;

    /**
     * Alle leden van groep toevoegen aan activiteit.
     *
     * @Route("/{activiteit}/addall")
     */
    public function addAllAction(Request $request, $activiteit)
    {
        /** @var $activiteit Activiteit */
        $activiteit = $this->activiteitDao->find($activiteit);

        $today = new \DateTime('today');
        $count = 0;

        if ($activiteit->getGroep()) {
            foreach ($activiteit->getGroep()->getKlantLidmaatschappen() as $lidmaatschap) {
                if ($lidmaatschap->getStartdatum() <= $today
                    && ($lidmaatschap->getEinddatum() > $today || !$lidmaatschap->getEinddatum())
                ) {
                    if (!$activiteit->getKlanten()->contains($lidmaatschap->getKlant())) {
                        $activiteit->addKlantDeelname(new KlantDeelname($activiteit, $lidmaatschap->getKlant()));
                        ++$count;
                    }
                }
            }
        }

        $this->activiteitDao->update($activiteit);
        $this->addFlash('success', $count.' deelnemers zijn toegevoegd.');

        return $this->redirectToRoute('ga_activiteiten_view', ['id' => $activiteit->getId()]);
    }
}
