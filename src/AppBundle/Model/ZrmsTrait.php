<?php

namespace AppBundle\Model;

use AppBundle\Entity\Zrm;
use Doctrine\ORM\Mapping as ORM;

trait ZrmsTrait
{
    /**
     * @var Zrm[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Zrm", cascade={"persist"})
     */
    protected $zrms;

    public function getZrms()
    {
        return $this->zrms;
    }

    public function addZrm(Zrm $zrm)
    {
        $this->zrms[] = $zrm;

        return $this;
    }
}
