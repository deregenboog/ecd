<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\DoelstellingRepository")
 * @ORM\Table(
 *     name="iz_doelstellingen",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="unique_project_jaar_stadsdeel_idx", columns={"project_id", "jaar", "stadsdeel"}),
 *         @ORM\UniqueConstraint(name="unique_project_jaar_categorie_idx", columns={"project_id", "jaar", "categorie"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Doelstelling
{
    public const CATEGORIE_CENTRALE_STAD = 'Centrale stad';
    public const CATEGORIE_FONDSEN = 'Fondsen';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @Gedmo\Versioned
     */
    private $project;

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
        return $this->jaar;
    }

    public function setJaar($jaar)
    {
        $this->jaar = $jaar;

        return $this;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;

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
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
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
