<?php

namespace AppBundle\Model;



use AppBundle\Exception\AppException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use function PHPUnit\Framework\throwException;

trait DossierStatusTrait
{
    /**
     * @ORM\Column(type="date")
     * @Assert\LessThanOrEqual("today",message="Datum van dossierstatus kan niet in de toekomst liggen")
     * @Gedmo\Versioned()
     */
    protected $datum;


    protected $entity;

    /**
     * @var null The classname of the class for which this dossierStatus is (ie. Deelnemer, Client, Slaper, Bezoeker...)
     */
    protected $entityClass = null;

    /**
     * @var null The class which reflects the 'Aangemeld' or 'Open' status.
     */
    protected $openClass = null;

    /**
     * @var null The class which reflects the 'Afgesloten' or 'Closed' status.
     */
    protected $closedClass = null;

    public function initializeDossierStatusTrait()
    {
        $this->mapClasses();
        if(null === $this->datum)
        {
            $this->datum = new \DateTime('today');
        }
    }


    public function getDatum(): ?\DateTime
    {
        return $this->datum;
    }

    public function setDatum(?\DateTime $datum): void
    {
        $this->datum = $datum;
    }

    public function getEntityClass(): string
    {
        if(null === $this->entityClass) throw new AppException("EntityClass must be set for dossierStatus entity to work properly.");
        return $this->entityClass;
    }

    public function getOpenClass(): string
    {
        if(null === $this->openClass) throw new AppException("OpenClass must be set for DossierStatus entity to work properly.");
        return $this->openClass;
    }

    public function getClosedClass(): string
    {
        if(null === $this->closedClass) throw new AppException("closedClass must be set for DossierStatus entity to work properly.");
        return $this->closedClass;
    }

    public function getEntity()
    {
        if(!$this->entity instanceof $this->entityClass) throw new AppException("Entity must be of type ".$this->entityClass." .");
        return $this->entity;
    }

    public function isOpen(): bool
    {
        return $this->isAangemeld();
    }

    public function isClosed(): bool
    {
        return $this->isAfgesloten();
    }

    public function getShortClassname(): string
    {
        $reflection = new \ReflectionClass($this);
        $shortName = $reflection->getShortName();
        return $shortName;
    }
    /**
     * @ORM\PostLoad
     */
    public function postLoad()
    {
        $this->initializeDossierStatusTrait();
    }

//    public function setEntity($entity): void
//    {
//        if(! $entity instanceof $this->entityClass) throw new AppException("Entity must be of type ".$this->entityClass." .");
//        $this->entity = $entity;
//    }


}
