<?php

namespace DagbestedingBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;

class DagdeelModel
{
    /**
     * @var string
     *
     * @Assert\Regex(pattern="/^[AZOV]{1}$/", message="Ongeldige waarde {{ value }} ingevuld. Vul A, Z, O of V in")
     */
    private $aanwezigheid;

    public function __construct($aanwezigheid = null)
    {
        $this->aanwezigheid = $aanwezigheid;
    }

    public function getAanwezigheid()
    {
        return $this->aanwezigheid;
    }

    public function setAanwezigheid($aanwezigheid)
    {
        $this->aanwezigheid = trim(strtoupper($aanwezigheid));

        return $this;
    }
}
