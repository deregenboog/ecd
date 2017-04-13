<?php

namespace HsBundle\Entity;

trait MemoSubjectTrait
{
    /**
     * @var Memo[]
     *
     * @ORM\ManyToMany(targetEntity="Memo", cascade={"persist"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     */
    protected $memos;

    /**
     * @return Memo[]
     */
    public function getMemos()
    {
        return $this->memos;
    }

    /**
     * @param Memo $memo
     * @return self
     */
    public function addMemo(Memo $memo)
    {
        $this->memos[] = $memo;

        return $this;
    }
}
