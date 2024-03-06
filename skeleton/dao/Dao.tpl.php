<?= "<?php\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

class <?= $class_name ?> extends AbstractDao implements <?= $interface_name."\n" ?>
{
    protected $class = <?= $entity_class ?>::class;

    protected $alias = '<?= $alias ?>';

    protected $paginationOptions = [
        'defaultSortFieldName' => $this->alias . '.id',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            $this->alias . '.id',
        ],
    ];

    public function findAll($page = null, FilterInterface $filter = null): ArrayCollection
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(<?= $entity_class ?> $entity): void
    {
        $this->doCreate($entity);
    }

    public function update(<?= $entity_class ?> $entity): void
    {
        $this->doUpdate($entity);
    }

    public function delete(<?= $entity_class ?> $entity): void
    {
        $this->doDelete($entity);
    }
}
