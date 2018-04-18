<?php

namespace AppBundle\Model;

use AppBundle\Entity\Zrm;
use Doctrine\ORM\Mapping as ORM;

trait ZrmTrait
{
    /**
     * @var Zrm
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Zrm", cascade={"persist"})
     */
    protected $zrm;

    public function getZrm()
    {
        return $this->zrm;
    }

    public function setZrm(Zrm $zrm)
    {
        $this->zrm = $zrm;

        return $this;
    }
}
