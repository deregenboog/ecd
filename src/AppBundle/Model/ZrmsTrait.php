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
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     */
    protected $zrms;

    public function getZrm()
    {
        return count($this->zrms) > 0 ? $this->zrms[0] : null;
    }

    public function getZrms()
    {
        return $this->zrms;
    }
}
