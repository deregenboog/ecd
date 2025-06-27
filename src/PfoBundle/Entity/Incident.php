<?php
namespace PfoBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\IncidentInterface;
use AppBundle\Model\TimestampableTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pfo_incidenten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Incident
{
    use TimestampableTrait;
    use IdentifiableTrait;
    
    /**
     * @ORM\Column(name="datum", type="date")
     *
     * @Assert\NotNull
     */
    private $datum;

    /**
     * @ORM\Column(name="remark", type="text", nullable=true)
     */
    private $opmerking;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $politie = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $ambulance = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $crisisdienst = false;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="incidenten", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull
     */
    private ?Client $client;

    public function getIncidentType(): string
    {
        return 'hs';
    }

    public function __construct(?Client $client = null)
    {
        if (null !== $client) {
            $this->setClient($client);
        }

        $this->setDatum(new \DateTime());
    }

    public function __toString()
    {
        return $this->getDatum()->format('d-m-Y');
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatum(): ?\DateTime
    {
        return $this->datum;
    }

    /**
     * @return Incident
     */
    public function setDatum($datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getOpmerking(): string
    {
        if(is_null($this->opmerking)) return "";
        return mb_convert_encoding($this->opmerking ?? "", 'ISO-8859-1','UTF-8');
    }

    /**
     * @return Incident
     */
    public function setOpmerking(?string $opmerking = "")
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');

        return $this;
    }

    public function isPolitie(): bool
    {
        return $this->politie;
    }

    public function setPolitie(bool $politie): Incident
    {
        $this->politie = $politie;

        return $this;
    }

    public function isAmbulance(): bool
    {
        return $this->ambulance;
    }

    public function setAmbulance(bool $ambulance): Incident
    {
        $this->ambulance = $ambulance;

        return $this;
    }

    public function isCrisisdienst(): bool
    {
        return $this->crisisdienst;
    }

    public function setCrisisdienst(bool $crisisdienst): Incident
    {
        $this->crisisdienst = $crisisdienst;

        return $this;
    }
}
