<?php

namespace App\Http\Object;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\UserDocumentEvent;

class DocumentStoreObject
{
    protected $content;
    protected $user_id;

    public function __construct($request)
    {
        $this->content = $request->input('content');
        $this->user_id = $request->input('user_id');
    }

    public function getArrayData()
    {
        return array(
            'content' => $this->content,
            'user_id' => $this->user_id,
            'created_by' => Auth::user()->id,
            'modified_by' => Auth::user()->id,
        );
    }

    public function eventPush()
    {
        event(new UserDocumentEvent('Tiene un nuevo documento disponible con el siguiente contenido: '.$this->content, $this->user_id));
    }
}
