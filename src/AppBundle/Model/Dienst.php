<?php

namespace AppBundle\Model;

use AppBundle\Entity\Medewerker;

class Dienst
{
    /**
     * @var string
     */
    private $naam;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $omschrijving;

    /**
     * @var string
     */
    private $toelichting;

    /**
     * @var \DateTime
     */
    private $van;

    /**
     * @var \DateTime
     */
    private $tot;

    /**
     * @var string
     */
    private $titelMedewerker;

    /**
     * @var string
     */
    private $medewerker;

    private $entity;

    public function __construct(
        ?string $naam = null,
        ?string $url = null,
        ?string $omschrijving = null,
        ?string $toelichting = null
    ) {
        $this->naam = $naam;
        $this->url = $url;
        $this->omschrijving = $omschrijving;
        $this->toelichting = $toelichting;
    }

    /**
     * @return string
     */
    public function getNaam()
    {
        return $this->naam;
    }

    /**
     * @param string $naam
     */
    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    /**
     * @return string
     */
    public function getOmschrijving()
    {
        return $this->omschrijving;
    }

    /**
     * @param string $omschrijving
     */
    public function setOmschrijving($omschrijving)
    {
        $this->omschrijving = $omschrijving;

        return $this;
    }

    public function getToelichting(): ?string
    {
        return $this->toelichting;
    }

    public function setToelichting(string $toelichting)
    {
        $this->toelichting = $toelichting;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getVan()
    {
        return $this->van;
    }

    public function setVan(\DateTime $van)
    {
        $this->van = $van;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTot()
    {
        return $this->tot;
    }

    public function setTot(\DateTime $tot)
    {
        $this->tot = $tot;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitelMedewerker()
    {
        return $this->titelMedewerker;
    }

    public function setTitelMedewerker(string $titelMedewerker)
    {
        $this->titelMedewerker = $titelMedewerker;

        return $this;
    }

    /**
     * @return string
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = (string) $medewerker;

        return $this;
    }

    public function setNaamMedewerker(string $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}
