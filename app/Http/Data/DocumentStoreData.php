<?php

namespace App\Http\Data;

use App\Document;

class DocumentStoreData
{
    protected $data_array;

    public function __construct($data_array)
    {
        $this->data_array = $data_array;
    }

    public function create()
    {
        return Document::create($this->data_array);
    }
}
