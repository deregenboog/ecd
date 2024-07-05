<?php

namespace AppBundle\Model;

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
     *
     * @param string $kpl
     */
    public function __construct(int $kpl, string $label, \Closure $closure, $humanDescription)
    {
        $this->label = $label;
        $this->kpl = $kpl;
        $this->closure = $closure;
        $this->humanDescription = $humanDescription;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getKpl(): int
    {
        return $this->kpl;
    }

    public function getClosure(): \Closure
    {
        return $this->closure;
    }

    public function getHumanDescription()
    {
        return $this->humanDescription;
    }

    public function setHumanDescription($humanDescription): void
    {
        $this->humanDescription = $humanDescription;
    }
}
