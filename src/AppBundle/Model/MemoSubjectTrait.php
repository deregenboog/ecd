<?php

namespace AppBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait MemoSubjectTrait
{
    /**
     * @var ArrayCollection<MemoInterface>
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Memo", cascade={"persist"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    protected $memos;

    /**
     * @return ArrayCollection<MemoInterface>
     */
    public function getMemos()
    {
        return $this->memos;
    }

    /**
     * @param MemoInterface $memo
     *
     * @return self
     */
    public function addMemo(MemoInterface $memo)
    {
        $this->memos[] = $memo;

        return $this;
    }

    /**
     * @param MemoInterface $memo
     *
     * @return self
     */
    public function removeMemo(MemoInterface $memo)
    {
        $this->memos->removeElement($memo);

        return $this;
    }
}
