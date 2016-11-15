<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Criteria;

/**
 * @Entity
 * @Table(name="hs_facturen")
 */
class HsFactuur
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @Column(type="date")
	 */
	private $datum;

	/**
	 * @Column(type="decimal", scale=2)
	 */
	private $bedrag;

	/**
	 * @var HsKlus
	 * @ManyToOne(targetEntity="HsKlus", inversedBy="hsFacturen")
	 */
	private $hsKlus;

	/**
	 * @var ArrayCollection|HsRegistratie[]
	 * @OneToMany(targetEntity="HsRegistratie", mappedBy="hsFactuur", orphanRemoval=true)
	 * @JoinColumn(onDelete="SET NULL")
	 */
	private $hsRegistraties;

	public function __construct(HsKlus $hsKlus = null)
	{
		$this->setDatum(new \DateTime());
		$this->setHsKlus($hsKlus);

		$criteria = Criteria::create()->where(Criteria::expr()->isNull('hsFactuur'));
		$this->setHsRegistraties($hsKlus->getHsRegistraties()->matching($criteria));
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

	public function getHsKlus()
	{
		return $this->hsKlus;
	}

	private function setHsKlus(HsKlus $hsKlus)
	{
		$this->hsKlus = $hsKlus;
		$hsKlus->getHsFacturen()->add($this);


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

	public function getHsRegistraties()
	{
		return $this->hsRegistraties;
	}

	private function setHsRegistraties(ArrayCollection $hsRegistraties)
	{
		$bedrag = 0.0;
		$this->hsRegistraties = $hsRegistraties;
		foreach ($hsRegistraties as $hsRegistratie) {
			$hsRegistratie->setHsFactuur($this);
			$bedrag += 2.5 * $hsRegistratie->getUren();
		}

		$this->bedrag = $bedrag;

		return $this;
	}

	public function getBedrag()
	{
		return $this->bedrag;
	}
}
