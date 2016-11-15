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
 * @Table(name="landen")
 */
class Land
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @Column(name="AFK2", type="string", length=5, nullable=false)
	 */
	private $afkorting2;

	/**
	 * @Column(name="AFK3", type="string", length=5, nullable=false)
	 */
	private $afkorting3;

	/**
	 * @Column(type="string")
	 */
	private $land;

	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $created;

	/**
	 * @Column(type="datetime", nullable=true)
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
