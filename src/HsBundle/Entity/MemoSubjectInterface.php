<?php

namespace HsBundle\Entity;

interface MemoSubjectInterface
{
    /**
     * @return Memo[]
     */
    public function getMemos();

    /**
     * @return self
     */
    public function addMemo(Memo $memo);

    /**
     * @return self
     */
    public function removeMemo(Memo $memo);
}
