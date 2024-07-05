<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait MemoSubjectTrait
{
    /**
     * @var Memo[]
     *
     * @ORM\ManyToMany(targetEntity="Memo", cascade={"persist"})
     *
     * @ORM\JoinTable(name="hs_klant_memo", inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     *
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
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
     * @return self
     */
    public function addMemo(Memo $memo)
    {
        $this->memos[] = $memo;

        return $this;
    }

    /**
     * @return self
     */
    public function removeMemo(Memo $memo)
    {
        $this->memos->removeElement($memo);

        return $this;
    }
}
