<?php

namespace App\Services\ModelService;

use App\Models\Media as Model;

class Video
{
    protected int $limit = 0;

    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAllPaginate(int $limit = 10, int $offset = 1)
    {
        return $this->model->notDeleted()->simplePaginate($limit, ['*'], 'page', $offset);
    }

    public function getByCate(int $cate_id = 0)
    {
        $this->model = $this->model->cateId($cate_id);

        return $this;
    }

    public function getByArea(int $area_id)
    {
        $this->model = $this->model->area($area_id);

        return $this;
    }

    public function getByYear(int $year)
    {
        $this->model = $this->model->year($year);

        return $this;
    }

    public function getByType(int $type_id)
    {
        $this->model = $this->model->typeId($type_id);

        return $this;
    }

    public function getByName(string|null $name = null)
    {
        $this->model = $this->model->name($name);

        return $this;
    }

    public function getOneById(int $id)
    {
        return $this->model->notDeleted()->findOrFail($id);
    }
}
