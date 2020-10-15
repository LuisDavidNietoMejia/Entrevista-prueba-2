<?php namespace App\Http\Data;

class DocumentUpdateData
{
    protected $data_array;
    protected $model;

    public function __construct($data_array, $model)
    {
        $this->data_array = $data_array;
        $this->model = $model;
    }

    public function update()
    {
        if ($this->model != null) {
          return $this->model->update($this->data_array);
        }
        return false;
    }
}
