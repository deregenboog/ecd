<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimestampableTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    /** @var ?DateTime Is set when preUpdate is called so other preUpdate events can check on the original unchanged field. */
    protected $modifiedBefore;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = $this->modified = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->modifiedBefore = $this->modified;
        $this->modified = new \DateTime();
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function getModifiedBeforePreUpdate()
    {
        return $this->modifiedBefore;
    }
}
