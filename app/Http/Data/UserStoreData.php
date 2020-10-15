<?php

namespace App\Http\Data;

use App\User;

class UserStoreData
{
    protected $data_array;

    public function __construct($data_array)
    {
        $this->data_array = $data_array;
    }

    public function create()
    {
        return User::create($this->data_array);
    }
}
