<?php

namespace AppBundle\Event;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Persisters\PersisterException;

class PreventSaveForDateRangeEventListener
{
    private $preventSaveBefore;

    /** @var DateTime */
    private $preventSaveBeforeDate;

    /** @var \DateTime */
    private $preventSaveAfterDate;

    /** @var Array */
    private $excludeEntities;

    /** @var bool  */
    private $enabled = true;

    /**
     * @param $preventSaveEnabled
     * @param $preventSaveBefore
     * @param $preventSaveAfter
     * @throws PersisterException Throws when item cannot be saved due to the constraints given.
     *
     * This class prevents entities to be modified when their original modify date is in a date range.
     * This way we can prevent that data gets modified while reports are in the making and get controlled by the accountant.
     * Makes use of LifecycleEvents and the Timestampeable trait. Entities should use this trait for this mechanism to work.
     */
    public function __construct($preventSaveEnabled,$preventSaveBefore,$preventSaveAfter, $excludeEntities) {
        if(new \DateTime($preventSaveEnabled) > new \DateTime()){
            return;
        }//not yet enabled.
        else if(null == $preventSaveBefore){
            $this->enabled = false;
            return;
        };

       $this->preventSaveBefore=$preventSaveBefore;
       $this->preventSaveBeforeDate = new \DateTime($preventSaveBefore);
       $this->preventSaveAfterDate = new \DateTime($preventSaveAfter);
       if(is_array($excludeEntities)) $this->excludeEntities = $excludeEntities;

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        if(!$this->enabled) return;

        $entity = $args->getEntity();

        $this->getAndCheckForDateField($entity,$args->getEntityManager());
        //if previous don't throw exception, then check for modified date.
        $this->getAndCheckModifiedDate($entity);

    }


    public function prePersist(LifecycleEventArgs $args)
    {
        if(!$this->enabled) return;

        //For new records; we should also check
        // if there is a date or datum veld in the entity and if that field is set in the period which should be invalidated?
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $this->getAndCheckForDateField($entity,$em);
    }


    private function getAndCheckForDateField($entity, EntityManager $em): void
    {
        if(in_array(get_class($entity),$this->excludeEntities)) return;

        $metadata = $em->getClassMetadata(get_class($entity));

        $matches = preg_grep('/^(.*)dat(e|um)+(.*)$/i', $metadata->getFieldNames());

        if(count($matches) < 1) return;

        foreach($matches as $fieldname)
        {
            $type = $metadata->getTypeOfField($fieldname);
            if(strpos($type,"date") !== false) //catch date and datetime as well..
            {
                //do the check check.
                $datumValue = $metadata->getFieldValue($entity,$fieldname);
                if($datumValue  < $this->preventSaveBeforeDate && $datumValue > $this->preventSaveAfterDate)
                {
                    throw new PersisterException('ECD is gesloten voor updates in het oude boekjaar in verband met de samenstelling van de cijfers door de accountant. Er kunnen geen items met een datum in het oude jaar worden opgeslagen.');
                }
            }
        }
        return;
    }

    protected function getAndCheckModifiedDate($entity): void
    {
        $class = get_class($entity);
        if(in_array(get_class($entity),$this->excludeEntities)) return;

        if(method_exists($entity,'getModified'))//uses timestampable trait
        {
            if($entity->getModifiedBeforePreUpdate() < $this->preventSaveBeforeDate && $entity->getModifiedBeforePreUpdate() > $this->preventSaveAfterDate)//no call to getModified as this gives the current DateTime.
            {
                throw new PersisterException('ECD is gesloten voor updates in het oude boekjaar in verband met de samenstelling van de cijfers door de accountant. Er kunnen geen items met een datum in het oude jaar worden opgeslagen.');
            }
        }
        return;
    }
}
