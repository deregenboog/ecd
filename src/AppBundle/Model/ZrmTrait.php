<?php

namespace AppBundle\Model;

use AppBundle\Entity\Zrm;
use Doctrine\ORM\Mapping as ORM;

trait ZrmTrait
{
    /**
     * @var Zrm[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Zrm", cascade={"persist"})
     * @ORM\JoinTable(
     *     joinColumns={@ORM\JoinColumn(unique=true)},
     *     inverseJoinColumns={@ORM\JoinColumn(unique=true)}
     * )
     */
    protected $zrms;

    public function getZrm()
    {
        if (!$this->zrms || 0 === count($this->zrms)) {
            return null;
        }

        return $this->zrms[0];
    }
}
