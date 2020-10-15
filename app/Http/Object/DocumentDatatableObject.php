<?php namespace App\Http\Object;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\User;
use App\Document;

class DocumentDatatableObject
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
        $documents = Document::join('users as user_relation', 'documents.user_id', '=', 'user_relation.id')
                            ->join('users as createdBy', 'documents.created_by', '=', 'createdBy.id')
                            ->join('users as modifiedBy', 'documents.modified_by', '=', 'modifiedBy.id')
                            ->select(
                              'documents.id',
                              'documents.content',
                              'user_relation.id as user_id',
                              'user_relation.name as user_name',
                              'user_relation.email as user_email',
                              'createdBy.name as createdBy',
                              'modifiedBy.name as modifiedBy'
                            )
                            ->where(function ($query) {
                                $query->orWhere('documents.content', 'like', '%'.$this->search.'%');
                                $query->orWhere('user_relation.name', 'like', '%'.$this->search.'%');
                                $query->orWhere('user_relation.email', 'like', '%'.$this->search.'%');
                            })
                            ->orderBy($this->field, $this->order)
                            ->paginate($this->count);

        return $documents;
    }
}
