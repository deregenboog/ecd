<?php

namespace AppBundle\Model;

trait SoftDeleteableTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @param \DateTime $deletedAt
     *
     * @return self
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return null !== $this->deletedAt;
    }
}
