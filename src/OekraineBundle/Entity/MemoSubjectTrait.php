<?php

namespace OekraineBundle\Entity;

use AppBundle\Model\MemoInterface;
use Doctrine\ORM\Mapping as ORM;

trait MemoSubjectTrait
{
    /**
     * @var MemoInterface[]
     *
     * @ORM\ManyToMany(targetEntity="OekraineBundle\Entity\Memo", cascade={"persist"})
     *
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     *
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    protected $memos;

    /**
     * @return MemoInterface[]
     */
    public function getMemos()
    {
        return $this->memos;
    }

    /**
     * @return self
     */
    public function addMemo(MemoInterface $memo)
    {
        $this->memos[] = $memo;

        return $this;
    }

    /**
     * @return self
     */
    public function removeMemo(MemoInterface $memo)
    {
        $this->memos->removeElement($memo);

        return $this;
    }
}
