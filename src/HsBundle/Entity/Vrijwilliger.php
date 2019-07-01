<?php

namespace HsBundle\Entity;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Debug\Exception\FatalErrorException;

/**
 * @ORM\Entity
 * @ORM\Table("hs_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Vrijwilliger extends Arbeider implements MemoSubjectInterface, DocumentSubjectInterface
{
    use HulpverlenerTrait, MemoSubjectTrait, DocumentSubjectTrait;

    /**
     * @var Vrijwilliger
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    /**
     * @var ArrayCollection|Klus[]
     * @ORM\ManyToMany(targetEntity="Klus", mappedBy="vrijwilligers")
     * @ORM\OrderBy({"startdatum": "desc"})
     */
    protected $klussen;

    public function __construct(AppVrijwilliger $vrijwilliger = null)
    {
        if ($vrijwilliger) {
            $this->vrijwilliger = $vrijwilliger;
        }

        parent::__construct();
    }

    public function __toString()
    {

        try {
            return NameFormatter::formatFormal($this->vrijwilliger);
        } catch (EntityNotFoundException $e) {
            return '(verwijderd)';
        }
        catch(FatalErrorException $e)
        {
            return '(verwijderd)';
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getVrijwilliger()
    {
        try
        {
            $this->vrijwilliger->getCreated();
        }
        catch(\Doctrine\ORM\EntityNotFoundException $e)
        {
            return null;
        }

        return null;
    }

    public function setVrijwilliger(AppVrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }

    public function addKlus(Klus $klus)
    {
        $this->klussen[] = $klus;

        return $this;
    }
}
