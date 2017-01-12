<?php

namespace OekBundle\Entity;

abstract class HsMemoSubject
{
    /**
     * {@inheritDoc}
     * @see \OekBundle\Entity\HsMemoSubjectInterface::getHsMemos()
     */
    public function getHsMemos()
    {
        return $this->hsMemos;
    }

    /**
     * {@inheritDoc}
     * @see \OekBundle\Entity\HsMemoSubjectInterface::addHsMemo()
     */
    public function addHsMemo(HsMemo $hsMemo)
    {
        $this->hsMemos[] = $hsMemo;

        return $this;
    }
}
