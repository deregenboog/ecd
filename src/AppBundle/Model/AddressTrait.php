<?php

namespace AppBundle\Model;

use AppBundle\Entity\GgwGebied;
use AppBundle\Entity\Postcode;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Entity\Werkgebied;
use AppBundle\Util\PostcodeFormatter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints as Assert;

trait AddressTrait
{
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $adres;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $plaats;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Werkgebied")
     * @ORM\JoinColumn(name="werkgebied", referencedColumnName="naam", nullable=true)
     * @Gedmo\Versioned
     */
    private $werkgebied;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GgwGebied")
     * @ORM\JoinColumn(name="postcodegebied", referencedColumnName="naam", nullable=true)
     * @Gedmo\Versioned
     */
    private $postcodegebied;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $mobiel;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $telefoon;

    public static function KoppelPostcodeWerkgebiedClosure(FormEvent $event, $em)
    {
        /* @var Persoon $data */
        $data = $event->getData();
        self::_koppelPostcodeWerkgebied($data,$em);

    }

    private static function _koppelPostcodeWerkgebied($data, $em)
    {
        if ($data->getPostcode()) {
            $data->setPostcode(PostcodeFormatter::format($data->getPostcode()));

            try {
                $postcode = $em->getRepository(Postcode::class)->find($data->getPostcode());
                if (null !== $postcode) {
                    $data
                        ->setWerkgebied($postcode->getStadsdeel())
                        ->setPostcodegebied($postcode->getPostcodegebied())
                    ;
                }
                else
                {
                    //wissen stadsdeel en postcodegebied; geen koppeling mogelijk; naw postcode buiten regio.
                    $data
                        ->setWerkgebied(null)
                        ->setPostcodegebied(null)
                    ;
                }
            } catch (\Exception $e) {
                // ignore
            }
        }
    }

    public function koppelPostcodeWerkgebied(EntityManager $entityManager)
    {
       self::_koppelPostcodeWerkgebied($this,$entityManager);
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    public function getAdres()
    {
        return $this->adres;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getPlaats()
    {
        return $this->plaats;
    }

    public function getMobiel()
    {
        return $this->mobiel;
    }

    public function getTelefoon()
    {
        return $this->telefoon;
    }

    public function setAdres($adres)
    {
        $this->adres = $adres;

        return $this;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function setPlaats($plaats)
    {
        $this->plaats = $plaats;

        return $this;
    }

    public function setMobiel($mobiel)
    {
        $this->mobiel = $mobiel;

        return $this;
    }

    public function setTelefoon($telefoon)
    {
        $this->telefoon = $telefoon;

        return $this;
    }

    public function getWerkgebied()
    {
        return $this->werkgebied;
    }

    public function setWerkgebied(Werkgebied $werkgebied = null)
    {
        $this->werkgebied = $werkgebied;

        return $this;
    }

    public function getPostcodegebied()
    {
        return $this->postcodegebied;
    }

    public function setPostcodegebied(GgwGebied $postcodegebied = null)
    {
        $this->postcodegebied = $postcodegebied;

        return $this;
    }
}
