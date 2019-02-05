<?php

namespace AppBundle\Service;

use AppBundle\Entity\Postcode;
use AppBundle\Filter\FilterInterface;

class PostcodeDao extends AbstractDao implements PostcodeDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'postcode.postcode',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'postcode.postcode',
            'stadsdeel.naam',
            'postcodegebied.naam',
        ],
    ];

    protected $class = Postcode::class;

    protected $alias = 'postcode';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->leftJoin($this->alias.'.stadsdeel', 'stadsdeel')
            ->leftJoin($this->alias.'.postcodegebied', 'postcodegebied')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * @param int $id
     *
     * @return Postcode
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Postcode $postcode
     */
    public function create(Postcode $postcode)
    {
        return $this->doCreate($postcode);
    }

    /**
     * @param Postcode $postcode
     */
    public function update(Postcode $postcode)
    {
        return $this->doUpdate($postcode);
    }

    /**
     * @param Postcode $postcode
     */
    public function delete(Postcode $postcode)
    {
        return $this->doDelete($postcode);
    }
}
