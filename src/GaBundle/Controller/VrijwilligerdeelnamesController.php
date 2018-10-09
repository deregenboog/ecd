<?php

namespace GaBundle\Controller;

use GaBundle\Entity\Activiteit;
use GaBundle\Entity\VrijwilligerDeelname;
use GaBundle\Form\VrijwilligerDeelnameType;
use GaBundle\Service\ActiviteitDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/vrijwilligerdeelnames")
 */
class VrijwilligerdeelnamesController extends DeelnamesController
{
    protected $entityClass = VrijwilligerDeelname::class;
    protected $formClass = VrijwilligerDeelnameType::class;
    protected $baseRouteName = 'ga_vrijwilligerdeelnames_';

    /**
     * @var VrijwilligerDeelnameDaoInterface
     *
     * @DI\Inject("GaBundle\Service\VrijwilligerDeelnameDao")
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
     * @Template
     */
    public function addAllAction(Request $request, $activiteit)
    {
        /** @var $activiteit Activiteit */
        $activiteit = $this->activiteitDao->find($activiteit);

        $today = new \DateTime('today');
        $count = 0;

        if ($activiteit->getGroep()) {
            foreach ($activiteit->getGroep()->getVrijwilligerLidmaatschappen() as $lidmaatschap) {
                if ($lidmaatschap->getStartdatum() <= $today
                    && ($lidmaatschap->getEinddatum() > $today || !$lidmaatschap->getEinddatum())
                ) {
                    if (!$activiteit->getVrijwilligers()->contains($lidmaatschap->getVrijwilliger())) {
                        $activiteit->addVrijwilligerDeelname(new VrijwilligerDeelname($activiteit, $lidmaatschap->getVrijwilliger()));
                        ++$count;
                    }
                }
            }
        }

        $this->activiteitDao->update($activiteit);
        $this->addFlash('success', $count.' vrijwilliger zijn toegevoegd.');

        return $this->redirectToRoute('ga_activiteiten_view', ['id' => $activiteit->getId()]);
    }
}
