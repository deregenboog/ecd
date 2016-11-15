<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Entity
 * @Table(name="nationaliteiten")
 */
class Nationaliteit
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @Column(type="string")
	 */
	private $afkorting;

	/**
	 * @Column(type="string")
	 */
	private $naam;

	/**
	 * @Column(type="datetime")
	 */
	private $created;

	/**
	 * @Column(type="datetime")
	 */
	private $modified;

	public function getId()
	{
		return $this->id;
	}

	public function getAfkorting()
	{
		return $this->afkorting;
	}

	public function getNaam()
	{
		return $this->naam;
	}
}
