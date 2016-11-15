<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @Entity
 * @Table(name="hs_profiel_groep")
 */
class HsProfielGroep
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
	private $naam;

	public function __toString()
	{
		return $this->naam;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getNaam()
	{
		return $this->naam;
	}

	public function setNaam($naam)
	{
		$this->naam = $naam;

		return $this;
	}
}
