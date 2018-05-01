<?php

namespace PfoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="PfoBundle\Repository\VerslagRepository")
 * @ORM\Table(name="pfo_verslagen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verslag
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="contact_type", type="string")
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $verslag;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var Client[]
     *
     * @ORM\ManyToMany(targetEntity="Client", inversedBy="verslagen")
     * @ORM\JoinTable(
     *     name="pfo_clienten_verslagen",
     *     joinColumns={@ORM\JoinColumn(name="pfo_verslag_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="pfo_client_id")}
     * )
     */
    private $clienten;

    public function __construct(Medewerker $medewerker = null)
    {
        if ($medewerker) {
            $this->medewerker = $medewerker;
        }
        $this->clienten = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s (%s, %s)', $this->onderwerp, $this->medewerker, $this->created->format('d-m-Y'));
    }

    public function getId()
    {
        return $this->id;
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
}
