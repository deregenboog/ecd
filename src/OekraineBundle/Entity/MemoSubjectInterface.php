<?php

namespace OekraineBundle\Entity;

use AppBundle\Model\MemoInterface;

interface MemoSubjectInterface
{
    /**
     * @return MemoInterface[]
     */
    public function getMemos();

    /**
     * @return self
     */
    public function addMemo(MemoInterface $memo);

    /**
     * @return self
     */
    public function removeMemo(MemoInterface $memo);
}
