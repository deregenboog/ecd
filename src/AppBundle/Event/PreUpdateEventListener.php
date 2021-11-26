<?php

namespace AppBundle\Event;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Persisters\PersisterException;

class PreUpdateEventListener
{
    private $preventSaveBefore;

    /** @var DateTime */
    private $preventSaveBeforeDate;

    /** @var \DateTime */
    private $preventSaveAfterDate;

    /** @var bool  */
    private $enabled = true;

    public function __construct($preventSaveEnabled,$preventSaveBefore,$preventSaveAfter) {
        if(new \DateTime($preventSaveEnabled) > new \DateTime()){
            $this->enabled = false;
            return;
        }//not yet enabled.
        else if(null == $preventSaveBefore){
            $this->enabled = false;
            return;
        };

       $this->preventSaveBefore=$preventSaveBefore;
       $this->preventSaveBeforeDate = new \DateTime($preventSaveBefore);
       $this->preventSaveAfterDate = new \DateTime($preventSaveAfter);


    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        if(!$this->enabled) return;

        $entity = $args->getEntity();
        if(method_exists($entity,'getModified'))//uses timestampable trait
        {
            if($entity->getModifiedBeforePreUpdate() < $this->preventSaveBeforeDate && $entity->getModifiedBeforePreUpdate() > $this->preventSaveAfterDate)//no call to getModified as this gives the current DateTime.
            {
                throw new PersisterException('ECD is gesloten voor updates in het oude boekjaar in verband met de samenstelling van de cijfers door de accountant. Wijzigingen kunnen niet worden opgeslagen.');
            }
        }


    }
}
