<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

// * ORM\Entity(repositoryClass="AppBundle\Repository\PrestatieRepository")
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DoelstellingRepository")
 *
 * @ORM\Table(name="doelstellingen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Doelstelling
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $repository;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $label;

    /**
     * @ORM\Column(type="integer")
     *
     * @Gedmo\Versioned
     */
    private $jaar;

    /**
     * @ORM\Column(type="integer")
     *
     * @Gedmo\Versioned
     *
     * @Assert\GreaterThan(0)
     */
    private $aantal = 0;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $kostenplaats;

    private $repositoryLabel;

    private $actueel;

    private $relativeAantal;

    public static $repos = [];

    public function __toString()
    {
        return sprintf('%s (%d)', $this->getRepositoryFriendlyName(), $this->jaar);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getJaar()
    {
        if (null == $this->jaar) {
            $this->jaar = date('Y');
        }

        return $this->jaar;
    }

    public function setJaar($jaar)
    {
        $this->jaar = $jaar;

        return $this;
    }

    public function getRepository(): ?string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    public function getRepositoryFriendlyName()
    {
        return explode('::', $this->repository)[1];
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

    public function setRepositoryLabel($label)
    {
        $this->repositoryLabel = $label;
    }

    public function getRepositoryLabel()
    {
        return $this->repositoryLabel;
    }

    public function setActueel($actueel)
    {
        $this->actueel = $actueel;
    }

    public function getActueel()
    {
        return $this->actueel;
    }

    public function getRelativeAantal()
    {
        return $this->relativeAantal;
    }

    public function setRelativeAantal($relativeAantal): void
    {
        $this->relativeAantal = $relativeAantal;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label): void
    {
        $this->label = $label;
    }

    public function getKostenplaats()
    {
        return $this->kostenplaats;
    }

    public function setKostenplaats($kostenplaats): void
    {
        $this->kostenplaats = $kostenplaats;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (null !== $this->repository) {
            //            if($this->kpi == null)
            //            {
            //                $context->buildViolation('KPI kan niet leeg zijn.')
            //                    ->atPath('kpi')
            //                    ->addViolation();
            //            }
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
