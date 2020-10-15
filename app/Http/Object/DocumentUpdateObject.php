<?php

namespace App\Http\Object;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Document;

class DocumentUpdateObject
{
    protected $content;
    protected $user_id;

    public function __construct($request)
    {
        $this->document_model = Document::where('id','=',$request->get('id'))->first();
        $this->content = $request->input('content');
        $this->user_id = $request->input('user_id');
    }

    public function getArrayData()
    {
        return array(
            'content' => $this->content,
            'user_id' => $this->user_id
        );
    }

    public function getModel(){
        return $this->document_model;
    }
}
