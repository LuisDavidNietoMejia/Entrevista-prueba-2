<?php namespace App\Http\Object;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\User;

class UserDatatableObject
{
    protected $field;
    protected $order;
    protected $count;
    protected $search;

    public function __construct($request)
    {
        $this->field = $request->get('field');
        $this->order = $request->get('order');
        $this->count = $request->get('count');
        $this->search = $request->get('search');
    }

    public function datatable()
    {
        $users = DB::table('users')->select(
                            'users.id',
                            'users.name',
                            'users.email'
                            )
                            ->where(function ($query) {
                                $query->orWhere('users.name', 'like', '%'.$this->search.'%');
                                $query->orWhere('users.email', 'like', '%'.$this->search.'%');
                            })
                            ->orderBy($this->field, $this->order)
                            ->paginate($this->count);

        return $users;
    }
}
