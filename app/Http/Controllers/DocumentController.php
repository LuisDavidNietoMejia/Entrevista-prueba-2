<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Library\ResponseJson;
use App\Http\Object\DocumentStoreObject;
use App\Http\Object\DocumentDatatableObject;
use App\Http\Object\DocumentUpdateObject;
use App\Http\Data\DocumentUpdateData;
use App\Http\Data\DocumentStoreData;
use App\Http\Requests\DocumentStoreRequest;
use App\Http\Requests\DocumentUpdateRequest;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('document.index');
    }

    public function store(DocumentStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $DocumentStoreObject = new DocumentStoreObject($request);
            $DocumentStoreData = new DocumentStoreData($DocumentStoreObject->getArrayData());
            $createdFlag = $DocumentStoreData->create();
            if ($createdFlag) {
                $DocumentStoreObject->eventPush();
                $message = "Documento creado con exito";
                DB::commit();
                return ResponseJson::success(['success'=> $message]);
            } else {
                $message = "Ocurrio un error registrando el documento";
                DB::rollback();
                return ResponseJson::error(['error'=> $message]);
            }
        } catch (\Trowable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function datatable(Request $request)
    {
        $DocumentDatatableObject = new DocumentDatatableObject($request);
        $data = $DocumentDatatableObject->datatable();
        if ($data == null) {
            return ResponseJson::error(['error'=> 'No se consiguieron usuarios registrados']);
        }
        return ResponseJson::success(['success'=> 'Consulta exitosa de los documents registrados'], ['documents' => $data]);
    }

    public function update(DocumentUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $DocumentUpdateObject = new DocumentUpdateObject($request);
            if ($DocumentUpdateObject->getModel() != null) {
                $DocumentUpdateData = new DocumentUpdateData($DocumentUpdateObject->getArrayData(),$DocumentUpdateObject->getModel());
                $updatedFlag = $DocumentUpdateData->update();
                if ($updatedFlag) {
                    $DocumentUpdateObject->eventPush();
                    $message = "Documento actualizado con exito";
                    DB::commit();
                    return ResponseJson::success(['success'=> $message]);
                } else {
                    $message = "Ocurrio un error actualizando el documento";
                    DB::rollback();
                    return ResponseJson::error(['error'=> $message]);
                }
            }
            else{
              $message = "No se consiguio en nuestros registros el documento a actualizar";
              DB::rollback();
              return ResponseJson::error(['error'=> $message]);
            }
        } catch (\Trowable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
