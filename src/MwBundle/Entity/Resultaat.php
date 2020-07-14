<?php
namespace MwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Resultaat
 * @package MwBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="mw_resultaten")
 * @Gedmo\Loggable
 */
class Resultaat {
    use ActivatableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $naam;


    /**
     * @ORM\ManyToMany(targetEntity="MwDossierStatus", mappedBy="resultaten")
     */
    private $mwDossierStatus;


    public function __toString():string {
        return (string) $this->naam;
    }
    /**
     * @return bool
     */
    public function isActief(): bool
    {
        return $this->actief;
    }

    /**
     * @param bool $actief
     * @return Resultaat
     */
    public function setActief(bool $actief): Resultaat
    {
        $this->actief = $actief;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Resultaat
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNaam()
    {
        return $this->naam;
    }

    /**
     * @param mixed $naam
     * @return Resultaat
     */
    public function setNaam($naam)
    {
        $this->naam = $naam;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMwDossierStatus()
    {
        return $this->mwDossierStatus;
    }

    /**
     * @param mixed $mwDossierStatus
     * @return Resultaat
     */
    public function setMwDossierStatus($mwDossierStatus)
    {
        $this->mwDossierStatus = $mwDossierStatus;
        return $this;
    }


}