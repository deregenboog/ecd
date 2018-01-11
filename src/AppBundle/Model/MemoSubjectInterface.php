<?php

namespace AppBundle\Model;

interface MemoSubjectInterface
{
    /**
     * @return Memo[]
     */
    public function getMemos();

    /**
     * @param Memo $memo
     *
     * @return self
     */
    public function addMemo(Memo $memo);

    /**
     * @param Memo $memo
     *
     * @return self
     */
    public function removeMemo(Memo $memo);
}
