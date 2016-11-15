<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @Entity
 * @Table(name="hs_profiel_code")
 */
class HsProfielCode
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @var string
	 * @Column(type="string")
	 */
	private $waarde;

	/**
	 * @var HsProfielGroep
	 * @ManyToOne(targetEntity="HsProfielGroep")
	 * @JoinColumn(nullable=false)
	 */
	private $hsProfielGroep;

	/**
	 * @var HsKlant
	 * @ManyToOne(targetEntity="HsKlant")
	 * @JoinColumn(nullable=false)
	 */
	private $hsKlant;

	public function __toString()
	{
		return sprintf('%s: %s', $this->hsProfielGroep, $this->waarde);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getWaarde()
	{
		return $this->waarde;
	}

	public function setWaarde($waarde)
	{
		$this->waarde = $waarde;

		return $this;
	}

	public function getHsProfielGroep()
	{
		return $this->hsProfielGroep;
	}

	public function setHsProfielGroep(HsProfielGroep $hsProfielGroep)
	{
		$this->hsProfielGroep = $hsProfielGroep;

		return $this;
	}

	public function getHsKlant()
	{
		return $this->hsKlant;
	}

	public function setHsKlant(HsKlant $hsKlant)
	{
		$this->hsKlant = $hsKlant;

		return $this;
	}
}
