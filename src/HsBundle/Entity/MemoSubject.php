<?php

namespace HsBundle\Entity;

abstract class MemoSubject
{
    /**
     * @see \HsBundle\Entity\MemoSubjectInterface::getMemos()
     */
    public function getMemos()
    {
        return $this->memos;
    }

    /**
     * @see \HsBundle\Entity\MemoSubjectInterface::addMemo()
     */
    public function addMemo(Memo $memo)
    {
        $this->memos[] = $memo;

        return $this;
    }
}
