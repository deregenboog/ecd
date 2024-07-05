<?php

namespace PfoBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="PfoBundle\Repository\VerslagRepository")
 *
 * @ORM\Table(name="pfo_verslagen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Verslag
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_type", type="string", length=50, nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535, nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $verslag;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var Client[]
     *
     * @ORM\ManyToMany(targetEntity="Client", inversedBy="verslagen")
     *
     * @ORM\JoinTable(
     *     name="pfo_clienten_verslagen",
     *     joinColumns={@ORM\JoinColumn(name="pfo_verslag_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="pfo_client_id")}
     * )
     */
    private $clienten;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct(?Medewerker $medewerker = null)
    {
        if ($medewerker) {
            $this->medewerker = $medewerker;
        }
        $this->clienten = new ArrayCollection();
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
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

    public function getVerslag()
    {
        return $this->verslag;
    }

    public function setVerslag($verslag)
    {
        $this->verslag = $verslag;

        return $this;
    }

    public function getClienten()
    {
        return $this->clienten;
    }

    public function addClient(Client $client)
    {
        $this->clienten[] = $client;

        return $this;
    }

    public function setClient(Client $client)
    {
        return $this->addClient($client);
    }
}
