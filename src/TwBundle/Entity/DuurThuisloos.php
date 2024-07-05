<?php

namespace TwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="tw_duurthuisloos")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class DuurThuisloos
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
    use TimestampableTrait;
    use NotDeletableTrait;

    /**
     * @var bool
     *
     * @ORM\Column(name="`active`", type="boolean", options={"default":1})
     *
     * @Gedmo\Versioned
     */
    protected $actief = true;

    /**
     * @todo Not used, remove both from entity and database.
     *
     * @ORM\Column(name="minVal", type="integer", nullable=true)
     */
    private $_minVal;

    /**
     * @todo Not used, remove both from entity and database.
     *
     * @ORM\Column(name="maxVal", type="integer", nullable=true)
     */
    private $_maxVal;

    private function mndJr($val)
    {
        if ($val < 12) {
            return $val.' mnd';
        } else {
            return ($val / 12).' Jr';
        }
    }
}
