<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Zrm;

trait ZrmTrait
{
    /**
     * @var Zrm[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Zrm", cascade={"persist"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     */
    protected $zrms;

    public function getZrms()
    {
        return $this->zrms;
    }

    public function addZrm(Zrm $zrm)
    {
        $this->getIzDeelnemer()->getKlant()->addZrm($zrm);
        $this->zrms[] = $zrm;

        return $this;
    }
}
