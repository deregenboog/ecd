<?php

namespace AppBundle\Model;

interface MemoSubjectInterface
{
    /**
     * @return MemoInterface[]
     */
    public function getMemos();

    /**
     * @param MemoInterface $memo
     *
     * @return self
     */
    public function addMemo(MemoInterface $memo);

    /**
     * @param MemoInterface $memo
     *
     * @return self
     */
    public function removeMemo(MemoInterface $memo);
}
