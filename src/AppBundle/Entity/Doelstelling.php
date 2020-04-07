<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

//* ORM\Entity(repositoryClass="AppBundle\Repository\PrestatieRepository")
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DoelstellingRepository")
 * @ORM\Table(name="doelstellingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Doelstelling
{
    const CATEGORIE_CENTRALE_STAD = 'Centrale stad';
    const CATEGORIE_FONDSEN = 'Fondsen';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string",nullable=false)
     * @Gedmo\Versioned
     */
    private $repository;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $kpi;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $jaar;

    /**
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Werkgebied")
     * @ORM\JoinColumn(name="stadsdeel", referencedColumnName="naam")
     * @Gedmo\Versioned
     */
    private $stadsdeel;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $aantal = 0;

    public static $repos = [];

    public function __toString()
    {
        return sprintf('%s (%d)', $this->project, $this->jaar);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getJaar()
    {
        if($this->jaar == null)
        {
            $this->jaar =  date('Y');
        }
        return $this->jaar;
    }

    public function setJaar($jaar)
    {
        $this->jaar = $jaar;

        return $this;
    }


    public function getCategorie()
    {
        return $this->categorie;
    }

    public function setCategorie($categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepository(): ?string
    {
        //repository not loaded earlier. get repository data (kpis)
//        if(!in_array($this->repository,self::$repos))
//        {
//            $r = new $this->repository;
//            $r->getKpis();
//            self::$repos[$this->repository] = $r->getKpis();
//        }
        return $this->repository;
    }

    /**
     * @param string $repository
     */
    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }



    public function getStadsdeel()
    {
        return $this->stadsdeel;
    }

    public function setStadsdeel($stadsdeel)
    {
        $this->stadsdeel = $stadsdeel;

        return $this;
    }

    public function getAantal()
    {
        return $this->aantal;
    }

    public function setAantal($aantal)
    {
        $this->aantal = $aantal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKpi()
    {
        return $this->kpi."--";
    }

    /**
     * @param mixed $kpi
     */
    public function setKpi($kpi): void
    {
        $this->kpi = $kpi;
    }



    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {

        if($this->repository !== null)
        {
            if($this->kpi == null)
            {
                $context->buildViolation('KPI kan niet leeg zijn.')
                    ->atPath('kpi')
                    ->addViolation();
            }
        }
        return;
        switch ($this->categorie) {
            case self::CATEGORIE_CENTRALE_STAD:
            case self::CATEGORIE_FONDSEN:
                if ($this->stadsdeel) {
                    $context->buildViolation('Stadsdeel is niet toegestaan voor categorie "Centrale stad" en "Fondsen".')
                        ->atPath('stadsdeel')
                        ->addViolation();
                }
                break;
            default:
                if (!$this->stadsdeel) {
                    $context->buildViolation('Stadsdeel is verplicht voor categorie "Stadsdeel".')
                        ->atPath('stadsdeel')
                        ->addViolation();
                }
                break;
        }
    }
}
