<?php

namespace HsBundle\Entity;

abstract class MemoSubject
{
    /**
     * {@inheritDoc}
     * @see \HsBundle\Entity\MemoSubjectInterface::getMemos()
     */
    public function getMemos()
    {
        return $this->memos;
    }

    /**
     * {@inheritDoc}
     * @see \HsBundle\Entity\MemoSubjectInterface::addMemo()
     */
    public function addMemo(Memo $memo)
    {
        $this->memos[] = $memo;

        return $this;
    }
}
