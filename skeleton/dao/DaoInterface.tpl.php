<?= "<?php\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

interface <?= $class_name."\n" ?>
{
    public function find($id): <?= $entity_class ?>;

    public function create(<?= $entity_class ?> $entity): void;

    public function update(<?= $entity_class ?> $entity): void;

    public function delete(<?= $entity_class ?> $entity): void;
}
