<?php

namespace HsBundle\Entity;

interface MemoSubjectInterface
{
    /**
     * @return Memo[]
     */
    public function getMemos();

    /**
     * @param Memo $memo
     * @return self
     */
    public function addMemo(Memo $memo);
}
