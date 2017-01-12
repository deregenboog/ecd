<?php

namespace HsBundle\Entity;

abstract class HsMemoSubject
{
    /**
     * {@inheritDoc}
     * @see \HsBundle\Entity\HsMemoSubjectInterface::getHsMemos()
     */
    public function getHsMemos()
    {
        return $this->hsMemos;
    }

    /**
     * {@inheritDoc}
     * @see \HsBundle\Entity\HsMemoSubjectInterface::addHsMemo()
     */
    public function addHsMemo(HsMemo $hsMemo)
    {
        $this->hsMemos[] = $hsMemo;

        return $this;
    }
}
