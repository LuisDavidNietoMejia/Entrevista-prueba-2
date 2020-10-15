<?php

namespace App\Http\Object;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;

class UserUpdateObject
{
    protected $name;
    protected $last_name;
    protected $email;
    protected $user_model;

    public function __construct($request)
    {
        $this->user_model = User::where('id','=',$request->get('id'))->first();
        $this->name = $request->input('name');
        $this->email = $request->input('email');
    }

    public function getArrayData()
    {
        return array(
            'name' => $this->name,
            'email' => $this->email
        );
    }

    public function getModel(){
        return $this->user_model;
    }
}
