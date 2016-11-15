<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="hs_vrijwilligers")
 * @HasLifecycleCallbacks
 */
class HsVrijwilliger
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @Column(type="boolean", nullable=false)
	 */
	private $dragend = false;

	/**
	 * @var Vrijwilliger
	 * @OneToOne(targetEntity="Vrijwilliger")
	 * @JoinColumn(nullable=false, unique=true)
	 */
	private $vrijwilliger;

	/**
	 * @var HsKlus[]
	 * @ManyToMany(targetEntity="HsKlus", mappedBy="hsVrijwilligers")
	 */
	private $hsKlussen;

	/**
	 * @var HsRegistratie
	 * @OneToMany(targetEntity="HsRegistratie", mappedBy="hsVrijwilliger")
	 * @JoinColumn(nullable=false)
	 */
	private $hsRegistraties;

	/**
	 * @Column(type="datetime")
	 */
	private $created;

	/**
	 * @Column(type="datetime")
	 */
	private $modified;

	public function __construct()
	{
		$this->hsRegistraties = new ArrayCollection();
		$this->hsKlussen = new ArrayCollection();
	}

	public function __toString()
	{
		return (string) $this->vrijwilliger;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getDragend()
	{
		return $this->dragend;
	}

	public function setDragend($dragend)
	{
		$this->dragend = $dragend;

		return $this;
	}

	public function getVrijwilliger()
	{
		return $this->vrijwilliger;
	}

	public function setVrijwilliger(Vrijwilliger $vrijwilliger)
	{
		$this->vrijwilliger = $vrijwilliger;

		return $this;
	}

	public function getHsKlussen()
	{
		return $this->hsKlussen;
	}

	public function getRegistraties(HsKlus $hsKlus)
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->eq('hsKlus', $hsKlus))
			->orderBy([
				'datum' => Criteria::ASC,
				'start' => Criteria::ASC,
				'eind' => Criteria::ASC,
			])
		;

		return $this->hsRegistraties->matching($criteria);
	}

	/**
	 * @PrePersist
	 */
	public function onPrePersist()
	{
		$this->created = $this->modified = new \DateTime();
	}

	/**
	 * @PreUpdate
	 */
	public function onPreUpdate()
	{
		$this->modified = new \DateTime();
	}
}
