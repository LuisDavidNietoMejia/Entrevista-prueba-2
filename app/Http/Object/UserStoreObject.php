<?php

namespace App\Http\Object;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserStoreObject
{
    protected $name;
    protected $last_name;
    protected $email;

    public function __construct($request)
    {
        $this->name = $request->input('name');
        $this->email = $request->input('email');
    }

    public function getArrayData()
    {
        return array(
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make('user_documents')
        );
    }


}
