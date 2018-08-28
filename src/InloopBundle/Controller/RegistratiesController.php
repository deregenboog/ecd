<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use AppBundle\Service\KlantDaoInterface;
use InloopBundle\Entity\Registratie;
use InloopBundle\Form\RegistratieFilterType;
use InloopBundle\Form\RegistratieType;
use InloopBundle\Service\LocatieDaoInterface;
use InloopBundle\Service\RegistratieDaoInterface;
use InloopBundle\Service\SchorsingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityNotFoundException;

/**
 * @Route("/registraties")
 */
class RegistratiesController extends AbstractController
{
    protected $title = 'Registraties';
    protected $entityName = 'registratie';
    protected $entityClass = Registratie::class;
    protected $formClass = RegistratieType::class;
    protected $filterFormClass = RegistratieFilterType::class;
    protected $baseRouteName = 'inloop_registraties_';

    /**
     * @var RegistratieDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\RegistratieDao")
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    protected $klantDao;

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\LocatieDao")
     */
    protected $locatieDao;

    /**
     * @var SchorsingDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\SchorsingDao")
     */
    protected $schorsingDao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("inloop.export.registraties")
     */
    protected $export;

    /**
     * @Route("/jsonCanRegister/{klant_id}/{locatie_id}")
     */
    public function jsonCanRegisterAction($klant_id, $locatie_id, $h = 1)
    {
        $klant = $this->klantDao->find($klant_id);
        $locatie = $this->locatieDao->find($locatie_id);

        $jsonVar = [
            'confirm' => false,
            'allow' => true,
            'message' => '',
        ];

        $sep = '';
        $separator = PHP_EOL.PHP_EOL;

        if ($locatie->isGebruikersruimte()) {
            $laatsteRegistratie = $this->dao->findLatestByKlantAndLocatie($klant, $locatie);
            if ($laatsteRegistratie instanceof Registratie
                && $laatsteRegistratie->getBinnen() < new \DateTime('-2 months')
                && ($locatie->getId() != $klant->getLaatsteIntake()->getGebruikersruimte()->getId()
                    || $klant->getLaatsteIntake()->getIntakedatum() < new \DateTime('-2 months')
                )
            ) {
                $jsonVar['allow'] = false;
                $jsonVar['message'] = 'Langer dan twee maanden niet geweest. Opnieuw aanmelden via het maatschappelijk werk.';

                return new JsonResponse($jsonVar);
            }
        }

        if ($locatie->isGebruikersruimte()
            && $klant->getLaatsteIntake()->isMagGebruiken()
            && !$klant->getLaatsteTbcControle()
        ) {
            $jsonVar['allow'] = false;
            $jsonVar['message'] = 'Deze klant heeft geen TBC controle gehad en kan niet worden ingecheckt bij een locatie met een gebruikersruimte.';

            return new JsonResponse($jsonVar);
        }

        if (!$locatie->isOpen()) {
            $jsonVar['allow'] = false;
            $jsonVar['message'] = 'Deze locatie is nog niet open, klant kan nog niet inchecken!';

            return new JsonResponse($jsonVar);
        }

        try {
            if ($klant->getLaatsteRegistratie()) {
                if (!$klant->getLaatsteRegistratie()->getBuiten()) {
                    if ($klant->getLaatsteRegistratie()->getLocatie() == $locatie) {
                        $jsonVar['allow'] = false;
                        $jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingecheckt op deze locatie.';
                    } else {
                        $jsonVar['confirm'] = true;
                        $jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingecheckt op een andere locatie. Toch inchecken?';
                        $sep = $separator;
                    }

                    $sep = $separator;
                } else {
                    $diff = $klant->getLaatsteRegistratie()->getBuiten()->diff(new \DateTime());

                    if ($diff->h < $h && 0 == $diff->d && 0 == $diff->m && 0 == $diff->y) {
                        $jsonVar['message'] .= $sep.
                        __('This client has been checked out less than an hour ago. '.
                            'Are you sure you want to register him/her again?', true);

                        $jsonVar['confirm'] = true;
                        $sep = $separator;
                    }
                }
            }
        } catch (EntityNotFoundException $e) {
            // laatste registratie is gearchiveerd, dus niet recent
        }

        if ($jsonVar['allow']) {
            $newIntakeNeeded = $klant->getLaatsteIntake()->getIntakedatum()->diff(new \DateTime())->days > 365;
            if ($newIntakeNeeded) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon heeft momenteel een verlopen intake (> 1 jaar geleden). Toch inchecken?';
                $sep = $separator;
                $jsonVar['confirm'] = true;
            }

            $actieveSchorsingen = $this->schorsingDao->findActiefByKlantAndLocatie($klant, $locatie);
            if (count($actieveSchorsingen) > 0) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon is momenteel op deze locatie geschorst. Toch inchecken?';
                $sep = $separator;
                $jsonVar['confirm'] = true;
            }

            $tbcValid = \Configure::read('TBC_months_period') * 30 * DAY;
            $newTbcCheckNeeded = (!$klant->getLaatsteTbcControle() || $klant->getLaatsteTbcControle()->diff(new \DateTime()) > $tbcValid);
            if ($newTbcCheckNeeded && $locatie->isTbcCheck()) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon heeft een nieuwe TBC-check nodig. Toch inchecken?';
                $jsonVar['confirm'] = true;
                $sep = $separator;
            }

            if (count($klant->getOpmerkingen()) > 0) {
                $laatsteOpmerking = end($klant->getOpmerkingen()->toArray());
                if (!$laatsteOpmerking->isGezien()) {
                    $jsonVar['message'] .= $sep.'Laatste opmerking ('.$laatsteOpmerking->getCreated()->format('d-m-Y').'): '.$laatsteOpmerking->getBeschrijving();
                    $jsonVar['confirm'] = true;
                    $sep = $separator;
                }
            }
        }

        return new JsonResponse($jsonVar);
    }
}
