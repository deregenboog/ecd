<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\MemoInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="inloop_memos")
 * @Gedmo\Loggable
 */
class Memo implements MemoInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $onderwerp;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $memo;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $intake = false;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $medewerker;

    public function __construct(Medewerker $medewerker = null)
    {
        if ($medewerker) {
            $this->medewerker = $medewerker;
        }
        $this->datum = new \DateTime();
    }

    public function __toString()
    {
        return sprintf('%s (%s, %s)', $this->onderwerp, $this->medewerker, $this->datum->format('d-m-Y'));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function isIntake()
    {
        return $this->intake;
    }

    public function setIntake($intake)
    {
        $this->intake = $intake;

        return $this;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getOnderwerp()
    {
        return $this->onderwerp;
    }

    public function setOnderwerp($onderwerp)
    {
        $this->onderwerp = $onderwerp;

        return $this;
    }

    public function getMemo()
    {
        return $this->memo;
    }

    public function setMemo($memo)
    {
        $this->memo = $memo;

        return $this;
    }
}
