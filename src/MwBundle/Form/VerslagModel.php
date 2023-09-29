<?php

namespace MwBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use InloopBundle\Entity\Locatie;
use MwBundle\Entity\Doorverwijzing;
use MwBundle\Entity\Inventarisatie;
use MwBundle\Entity\Verslag;
use MwBundle\Entity\Verslaginventarisatie;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class VerslagModel
{
    private $verslag;
    private $inventarisaties;
    private $data = [];


    public function getVerslag()
    {
        return $this->verslag;
    }

    /**
     * @Assert\NotNull
     */
    public function getDatum()
    {
        return $this->verslag->getDatum();
    }

    public function setDatum(\DateTime $datum)
    {
        return $this->verslag->setDatum($datum);
    }

    /**
     * @Assert\NotBlank
     */
    public function getOpmerking()
    {
        return $this->getVerslag()->getOpmerking();
    }

    public function setOpmerking($opmerking)
    {
        return $this->verslag->setOpmerking($opmerking);
    }

    /**
     * @Assert\NotNull
     */
    public function getKlant()
    {
        return $this->verslag->getKlant();
    }

    public function setKlant(Klant $klant)
    {
        return $this->verslag->setKlant($klant);
    }

    /**
     * @Assert\NotNull
     */
    public function getLocatie()
    {
        return $this->verslag->getLocatie();
    }

    public function setLocatie(Locatie $locatie)
    {
        return $this->verslag->setLocatie($locatie);
    }

    /**
     * @Assert\NotNull
     */
    public function getMedewerker()
    {
        return $this->verslag->getMedewerker();
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        return $this->verslag->setMedewerker($medewerker);
    }

    /**
     * @Assert\NotNull
     */
    public function getAccessType()
    {
        return $this->verslag->getAccess();
    }

    public function setAccessType($accessType)
    {
        $this->verslag->setAccess($accessType);
    }

    /**
     * @Assert\NotNull
     */
    public function getContactsoort()
    {
        return $this->verslag->getContactsoort();
    }

    public function setContactsoort(Contactsoort $contactsoort)
    {
        return $this->verslag->setContactsoort($contactsoort);
    }

    /**
     * @Assert\Type("integer")
     */
    public function getDuur()
    {
        return $this->verslag->getDuur();
    }

    public function setDuur($duur)
    {
        return $this->verslag->setDuur($duur);
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (1 === preg_match('/face/i', (string) $this->getContactsoort())
            && is_null($this->getDuur())
        ) {
            $context->buildViolation('Geef het aantal minuten op')
                ->atPath('duur')
                ->addViolation();
        } elseif (0 === preg_match('/face/i', (string) $this->getContactsoort())
            && !is_null($this->getDuur())
            ) {
            $context->buildViolation('Geef alleen het aantal minuten op voor contactsoort face-to-face')
                    ->atPath('duur')
                    ->addViolation();
        }
    }

    public function __construct(Verslag $verslag, array $inventarisaties)
    {
        $this->verslag = $verslag;
        $this->inventarisaties = $inventarisaties;

        foreach ($this->inventarisaties as $categorieId => $inventarisaties) {
            $this->data['inventarisatie_'.$categorieId] = null;
            foreach ($inventarisaties as $key => $inventarisatie) {
                if ('rootName' === $key) {
                    continue;
                }
                switch ($inventarisatie->getActie()) {
                    case 'Doorverwijzer':
                        $this->data['inventarisatie_doorverwijzer_'.$inventarisatie->getId()] = null;
                        break;
                    case 'Trajecthouder':
                        $this->data['inventarisatie_trajecthouder_'.$inventarisatie->getId()] = null;
                        break;
                    case 'D':
                    case 'S':
                    case 'N':
                    default:
                        break;
                }
            }
        }

        foreach ($this->verslag->getVerslaginventarisaties() as $verslaginventarisatie) {
            switch ($verslaginventarisatie->getInventarisatie()->getActie()) {
                case 'Doorverwijzer':
                    $key = 'inventarisatie_doorverwijzer_'.$verslaginventarisatie->getInventarisatie()->getId();
                    $this->data[$key] = $verslaginventarisatie->getDoorverwijzing();

                    $key = 'inventarisatie_'.$verslaginventarisatie->getInventarisatie()->getRoot()->getId();
                    $this->data[$key] = $verslaginventarisatie->getInventarisatie();
                    break;
                case 'Trajecthouder':
                    $key = 'inventarisatie_trajecthouder_'.$verslaginventarisatie->getInventarisatie()->getId();
                    $this->data[$key] = $verslaginventarisatie->getDoorverwijzing();

                    $key = 'inventarisatie_'.$verslaginventarisatie->getInventarisatie()->getRoot()->getId();
                    $this->data[$key] = $verslaginventarisatie->getInventarisatie();
                    break;
                case 'S':
                    $key = 'inventarisatie_'.$verslaginventarisatie->getInventarisatie()->getRoot()->getId();
                    $this->data[$key] = $verslaginventarisatie->getInventarisatie();
                    break;
                case 'D':
                case 'N':
                default:
                    break;
            }
        }
    }

    public function __get($property)
    {
        return $this->data[$property];
    }

    public function __set($property, $value)
    {
        $this->data[$property] = $value;

        return $this;
    }

    public function syncInventarisaties()
    {
        // remove empty items
        $this->data = array_filter($this->data);

        $verslaginventarisaties = [];
        foreach ($this->data as $property => $value) {
            $matches = [];
            if (preg_match('/^inventarisatie_(doorverwijzer|trajecthouder)_(\d+)$/', $property, $matches)) {
                assert($value instanceof Doorverwijzing);
                $id = $matches[2];
                $inventarisatie = $this->getInventarisatie($id);
                $verslaginventarisaties[$inventarisatie->getId()] = new Verslaginventarisatie($this->verslag, $inventarisatie, $value);
            } elseif (preg_match('/^inventarisatie_(\d+)$/', $property, $matches)) {
                assert($value instanceof Inventarisatie);
                if (array_key_exists($value->getId(), $verslaginventarisaties)) {
                    continue;
                }
                $verslaginventarisaties[$value->getId()] = new Verslaginventarisatie($this->verslag, $value);
            }
        }

        $this->verslag->setVerslaginventarisaties($verslaginventarisaties);
    }

    private function getInventarisatie($id)
    {
        foreach ($this->inventarisaties as $categoryId => $inventarisaties) {
            foreach ($inventarisaties as $key => $inventarisatie) {
                if ('rootName' === $key) {
                    continue;
                }
                if ($id == $inventarisatie->getId()) {
                    return $inventarisatie;
                }
            }
        }

        throw new \LogicException('This should never be reached!');
    }
}
