<?php namespace App\Http\Object;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\User;

class UserSelectObject
{

    public function select()
    {
        $users = DB::table('users')->select(
                            'users.id',
                            'users.name'
                            )
                            ->get();

        return $this->getArrayData($users);
    }

    public function getArrayData($users){
      $arrayData = [];
      foreach ($users as $key => $value) {
            $arrayData[] = [
              'id' => $value->id,
              'name' => $value->name
            ];
      }
      return $arrayData;
    }
}
