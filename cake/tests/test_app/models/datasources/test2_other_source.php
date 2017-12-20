<?php

class Test2OtherSource extends DataSource
{
    public function describe($model)
    {
        return compact('model');
    }

    public function listSources()
    {
        return ['test_source'];
    }

    public function create($model, $fields = [], $values = [])
    {
        return compact('model', 'fields', 'values');
    }

    public function read($model, $queryData = [])
    {
        return compact('model', 'queryData');
    }

    public function update($model, $fields = [], $values = [])
    {
        return compact('model', 'fields', 'values');
    }

    public function delete($model, $id)
    {
        return compact('model', 'id');
    }
}
