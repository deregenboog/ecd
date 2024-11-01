<?php

namespace AppBundle\Entity;

use AppBundle\Model\AddressTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\PersonTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\MappedSuperclass
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 *
 * @Gedmo\SoftDeleteable
 */
class Persoon
{
    use IdentifiableTrait;
    use PersonTrait;
    use AddressTrait;
    use TimestampableTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\Column(name="BSN", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $bsn;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="Medewerker", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @var Land
     *
     * @ORM\ManyToOne(targetEntity="Land", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false, options={"default":1})
     *
     * @Gedmo\Versioned
     */
    protected $land;

    /**
     * @var Nationaliteit
     *
     * @ORM\ManyToOne(targetEntity="Nationaliteit",cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false, options={"default":1})
     *
     * @Gedmo\Versioned
     */
    protected $nationaliteit;

    /**
     * @ORM\Column(name="geen_post", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $geenPost = false;

    /**
     * @ORM\Column(name="geen_email", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $geenEmail = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $opmerking;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     *
     * @Gedmo\Versioned
     */
    protected $disabled = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function isGeenPost()
    {
        return $this->geenPost;
    }

    public function isGeenEmail()
    {
        return $this->geenEmail;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getLand()
    {
        return $this->land;
    }

    public function setLand(Land $land)
    {
        $this->land = $land;

        return $this;
    }

    public function getBsn()
    {
        return $this->bsn;
    }

    public function setBsn($bsn)
    {
        $this->bsn = $bsn;

        return $this;
    }

    public function getNationaliteit()
    {
        return $this->nationaliteit;
    }

    public function setNationaliteit(Nationaliteit $nationaliteit)
    {
        $this->nationaliteit = $nationaliteit;

        return $this;
    }

    public function getGeenPost()
    {
        return $this->geenPost;
    }

    public function setGeenPost($geenPost)
    {
        $this->geenPost = $geenPost;

        return $this;
    }

    public function getGeenEmail()
    {
        return $this->geenEmail;
    }

    public function setGeenEmail($geenEmail)
    {
        $this->geenEmail = $geenEmail;

        return $this;
    }

    public function getOpmerking(): string
    {
        if(is_null($this->opmerking)) return "";
        return mb_convert_encoding($this->opmerking, 'ISO-8859-1','UTF-8');
    }

    public function setOpmerking(string $opmerking = "")
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');

        return $this;
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function isJarigVandaag(): bool
    {
        $today = new \DateTime();
        $birthdate = $this->getGeboortedatum();
        if(!$birthdate instanceof \DateTime ) return false;

        return $birthdate->format('m-d') === $today->format('m-d');
    }

    /**
     * SoftDeleteable, so it's safe to return true.
     *
     * @return bool
     */
    public function isDeletable()
    {
        return true;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (!$this->voornaam && !$this->achternaam
            || (strlen(trim($this->voornaam)) < 1 && strlen(trim($this->achternaam)) < 1)
        ) {
            $context->buildViolation('Het is verplicht een voor- of achternaam in te vullen.')
                ->atPath('achternaam')
                ->addViolation();
        }
    }
}
