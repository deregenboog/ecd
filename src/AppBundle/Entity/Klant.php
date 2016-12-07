<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="klanten")
 */
class Klant extends Persoon
{
    /**
     * @ORM\Column(name="MezzoID", type="integer")
     */
    private $mezzoId = 0;

    /**
     * @ORM\Column(name="laatste_TBC_controle", type="date")
     */
    private $laatsteTbcControle;

    /**
     * @ORM\Column(name="laste_intake_id", type="integer")
     */
    private $laatsteIntakeId;

    /**
     * @ORM\Column(name="laatste_registratie_id", type="integer")
     */
    private $laatsteRegistratieId;

    /**
     * @ORM\Column(name="last_zrm", type="date")
     */
    private $laatsteZrm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $overleden;

    public function getLaatsteZrm()
    {
        return $this->laatsteZrm;
    }

    public function setLaastseZrm(\DateTime $laatsteZrm)
    {
        $this->laatsteZrm = $laatsteZrm;

        return $this;
    }
}
