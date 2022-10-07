<?php

namespace AppBundle\Event;

use AppBundle\Exception\UserException;
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
    private $excludeEntities = [];

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
       if(is_array($excludeEntities)) $this->excludeEntities = $excludeEntities;

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        if(!$this->enabled) return;

        $entity = $args->getEntity();

        $this->getAndCheckForDateField($entity,$args->getEntityManager());
        //if previous don't throw exception, then check for modified date.
//        $this->getAndCheckModifiedDate($entity);

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

    public function preRemove(LifecycleEventArgs $args)
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
        $uow = $em->getUnitOfWork();

        //if a sort of datefield exist:
        if((is_array($matches) || $matches instanceof \Countable ? count($matches) : 0) > 0) {
            $changeset = $uow->getEntityChangeSet($entity);
            foreach ($matches as $fieldname) {
                $type = $metadata->getTypeOfField($fieldname);
                if (strpos($type, "date") !== false) //catch date and datetime as well..
                {

                    $identifier = $metadata->getSingleIdentifierFieldName();
                    $idValue = $metadata->getFieldValue($entity,$identifier);


                    //do the check check this works only for updates; not for persists...
                    if ($idValue !== null && !array_key_exists($fieldname, $changeset)) {
                        continue;
                    } else if($idValue !== null) {
                        $prevValue = $changeset[$fieldname][0];
                        $newValue = $changeset[$fieldname][1];
                        if (($newValue > $this->preventSaveAfterDate && $newValue < $this->preventSaveBeforeDate)
                            && !($prevValue > $this->preventSaveAfterDate && $prevValue < $this->preventSaveBeforeDate)) {
                            //nieuwe waarde ligt in de range... Mag alleen als oude waarde dat ook lag; zo niet: error.

                            throw new UserException('ECD is gesloten voor updates in het oude boekjaar in verband met de samenstelling van de cijfers door de accountant. Er kunnen geen items met een datum in het oude jaar worden gewijzigd.');
                        }
                    }
                    else //idValue === null
                    {
//                        $prevValue = new \DateTime(null);
                        $newValue = $metadata->getFieldValue($entity,$fieldname);
                        if (($newValue > $this->preventSaveAfterDate && $newValue < $this->preventSaveBeforeDate)
//                            && !($prevValue > $this->preventSaveAfterDate && $prevValue < $this->preventSaveBeforeDate)
                        ) {
                            //gebeoortedatum is uitzondering en een die we vaak tegenkomen. of we moeten op veldniveau kunnen uitsluiten
                            if($fieldname == 'geboortedatum') return;
                            //nieuwe waarde ligt in de range... Mag alleen als oude waarde dat ook lag; zo niet: error.
                            $details = "";
                            $details .= "Fieldname: $fieldname. Entity: ".get_class($entity).". Value: ".$newValue->format("Y-d-m")."ID: ";
//                            $details .= var_export($entity,true);
                            throw new UserException($details.'ECD is gesloten voor updates in het oude boekjaar in verband met de samenstelling van de cijfers door de accountant. Er kunnen geen items met een datum in het oude jaar worden gewijzigd.');
                        }
                    }
                }
            }
        }//no date fields in entity.


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

    /**
     * @param $uow
     * @param $em
     * @throws PersisterException
     *
     * !! Werkt niet (methodisch); laat even staan voor als later. (2021/12/24 JTB)
     */
    private function checkForLinkedEntities($uow,$em)
    {
        /**
         * Check for linkedEntities if datefield exist;
         */
        $identityMap = $uow->getIdentityMap();
        foreach($identityMap as $linkedEntities)
        {
            foreach($linkedEntities as $linkedEntity){
                $leMetadata = $em->getClassMetadata(get_class($linkedEntity));
                $leMatches = preg_grep('/^(.*)dat(e|um)+(.*)$/i', $leMetadata->getFieldNames());
                if((is_array($leMatches) || $leMatches instanceof \Countable ? count($leMatches) : 0)<1) continue;
                foreach($leMatches as $leFieldname)
                {
                    $type = $leMetadata->getTypeOfField($leFieldname);
                    if(strpos($type,"date") !== false) //catch date and datetime as well..
                    {
                        $datumValue = $leMetadata->getFieldValue($linkedEntity,$leFieldname); //werkt niet bij relaties; lazy loading?.
                        if(null == $datumValue)
                        {
                            $methodName = "get".ucfirst($leFieldname);
                            if(method_exists($linkedEntity,$methodName))
                            {
                                $datumValue = $linkedEntity->{$methodName}(); //dan maar zo...
                            }
                        }

                        if($datumValue  < $this->preventSaveBeforeDate && $datumValue > $this->preventSaveAfterDate)
                        {
                            $a=1;
                            throw new PersisterException('ECD is gesloten voor updates in het oude boekjaar in verband met de samenstelling van de cijfers door de accountant. Er kunnen geen items met een datum in het oude jaar worden gewijzigd.');
                        }
                    }
                }
            }

        }
    }
}
