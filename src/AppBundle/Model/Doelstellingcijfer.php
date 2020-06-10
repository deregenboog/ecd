<?php

namespace AppBundle\Model;

use AppBundle\Entity\Doelstelling;

class Doelstellingcijfer
{

    /**
     * @var string Userfriendly label of name
     */
    private $label;

    /** @var int Kostenplaats in externe administratie */
    private $kpl;

    private $closure;

    private $humanDescription;

    /**
     * Doelstellingcijfer constructor.
     * @param string $kpl
     * @param string $label
     * @param \Closure $closure
     */
    public function __construct(int $kpl, string $label, \Closure $closure, $humanDescription)
    {

        $this->label = $label;
        $this->kpl = $kpl;
        $this->closure = $closure;
        $this->humanDescription = $humanDescription;
    }



    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }


    /**
     * @return int
     */
    public function getKpl(): int
    {
        return $this->kpl;
    }

    /**
     * @return \Closure
     */
    public function getClosure(): \Closure
    {
        return $this->closure;
    }

    /**
     * @return mixed
     */
    public function getHumanDescription()
    {
        return $this->humanDescription;
    }

    /**
     * @param mixed $humanDescription
     */
    public function setHumanDescription($humanDescription): void
    {
        $this->humanDescription = $humanDescription;
    }



}
