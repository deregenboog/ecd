<?php

namespace MwBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\Table(name="inventarisaties")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Tree(type="nested")
 * @Gedmo\Loggable
 */
class Inventarisatie
{
    use IdentifiableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="`order`", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $order;

    /**
     * @var Inventarisatie
     *
     * @ORM\ManyToOne(targetEntity="Inventarisatie", inversedBy="children")
     * @Gedmo\TreeParent
     * @Gedmo\Versioned
     */
    private $parent;

    /**
     * @var Inventarisatie[]
     *
     * @ORM\OneToMany(targetEntity="Inventarisatie", mappedBy="parent")
     * @ORM\OrderBy({"left" = "ASC"})
     */
    private $children;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $actief = true;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $titel;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $actie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @var int
     *
     * @ORM\Column(name="lft", type="integer", nullable=true)
     * @Gedmo\TreeLeft
     * @Gedmo\Versioned
     */
    private $left;

    /**
     * @var int
     *
     * @ORM\Column(name="rght", type="integer", nullable=true)
     * @Gedmo\TreeRight
     * @Gedmo\Versioned
     */
    private $right;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", length=3, nullable=true)
     * @Gedmo\TreeLevel
     * @Gedmo\Versioned
     */
    private $depth;

    /**
     * @var Inventarisatie
     *
     * @ORM\OneToMany(targetEntity="Verslaginventarisatie", mappedBy="inventarisatie")
     */
    private $verslaginventarisaties;

    public function __toString()
    {
        return (string) $this->titel;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Inventarisatie
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Inventarisatie $parent
     */
    public function setParent(self $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count((array) $this->children) > 0;
    }

    /**
     * @return bool
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function getActief()
    {
        return $this->actief;
    }

    /**
     * @param bool $actief
     */
    public function setActief($actief)
    {
        $this->actief = (bool) $actief;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * @param string $titel
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;

        return $this;
    }

    /**
     * @return string
     */
    public function getActie()
    {
        return $this->actie;
    }

    /**
     * @param string $actie
     */
    public function setActie($actie)
    {
        $this->actie = $actie;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartdatum()
    {
        return $this->startdatum;
    }

    /**
     * @param \DateTime $startdatum
     */
    public function setStartdatum($startdatum)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEinddatum()
    {
        return $this->einddatum;
    }

    /**
     * @param \DateTime $einddatum
     */
    public function setEinddatum($einddatum)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    /**
     * @return int
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param int $left
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * @return int
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param int $right
     */
    public function setRight($right)
    {
        $this->right = $right;

        return $this;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    public function isRoot()
    {
        return is_null($this->parent);
    }

    public function getRoot()
    {
        $current = $this;
        while ($parent = $current->getParent()) {
            $current = $parent;
        }

        return $current;
    }

    public function getPath($minDepth = 0)
    {
        $current = $this;

        $parts = [$current->getTitel()];
        while ($parent = $current->getParent()) {
            if ($parent->getDepth() < $minDepth) {
                break;
            }
            $parts[] = $parent->getTitel();
            $current = $parent;
        }

        return $parts;
    }
}
