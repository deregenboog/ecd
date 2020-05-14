<?php

namespace AppBundle\Model;



class Doelstellingcijfer
{
    /**
     * @var string system name for this doelstellingcijfer. Used in repository to retrieve the right cijfer. No spaces allowed.
     */
    private $name;

    /**
     * @var string Userfriendly label of name
     */
    private $label;

    /**
     * @var string Which repositoryClass to retrieve the cijfer from.
     */
    private $repositoryName;

    /** @var int Kostenplaats in externe administratie */
    private $kpl;

    /**
     * Doelstellingcijfer constructor.
     * @param string $name
     * @param string $label
     * @param string $repositoryName
     */
    public function __construct(int $kpl, string $name, string $label, string $repositoryName)
    {
        $this->name = $name;
        $this->label = $label;
        $this->repositoryName = $repositoryName;
        $this->kpl = $kpl;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getRepositoryName(): string
    {
        return $this->repositoryName;
    }

    /**
     * @param string $repositoryName
     */
    public function setRepositoryName(string $repositoryName): void
    {
        $this->repositoryName = $repositoryName;
    }

    /**
     * @return int
     */
    public function getKpl(): int
    {
        return $this->kpl;
    }

    /**
     * @param int $kpl
     */
    public function setKpl(int $kpl): void
    {
        $this->kpl = $kpl;
    }



}
