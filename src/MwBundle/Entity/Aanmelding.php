<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Aanmelding extends MwDossierStatus
{
    public function __toString()
    {
        $return = sprintf(
            'Aanmelding op %s door %s',
            $this->datum->format('d-m-Y'),
            $this->medewerker
        );
        if ($this->project) {
            $return .= sprintf(' bij %s',
                $this->project,
            );
        }

        return $return;
    }

    /**
     * @PrePersist
     */
    public function onPrePersist(LifecycleEventArgs $event)
    {
        $this->created = $this->modified = new \DateTime();
        if (false === empty($this->binnenViaOptieKlant)) {
            return;
        }
        $this->binnenViaOptieKlant = $event->getEntityManager()->getReference('MwBundle:BinnenViaOptieKlant', 0);
    }

    /**
     * @return void
     *
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        parent::parentValidate($context, $payload);
    }
}
