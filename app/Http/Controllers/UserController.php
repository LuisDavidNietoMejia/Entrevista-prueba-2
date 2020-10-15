<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Library\ResponseJson;
use App\Http\Object\UserStoreObject;
use App\Http\Object\UserDatatableObject;
use App\Http\Object\UserUpdateObject;
use App\Http\Object\UserSelectObject;
use App\Http\Data\UserUpdateData;
use App\Http\Data\UserStoreData;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('user.index');
    }

    public function select(Request $request){
       $UserSelectObject = new UserSelectObject($request);
       $data = $UserSelectObject->select();
       $message = 'Consulta exitosa de todos los usuarios';
       return ResponseJson::success(['success'=> $message],['users' => $data]);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $UserStoreObject = new UserStoreObject($request);
            $UserStoreData = new UserStoreData($UserStoreObject->getArrayData());
            $createdFlag = $UserStoreData->create();
            if ($createdFlag) {
                $message = "Usuario creado con exito";
                DB::commit();
                return ResponseJson::success(['success'=> $message]);
            } else {
                $message = "Ocurrio un error registrando el usuario";
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
        $UserDatatableObject = new UserDatatableObject($request);
        $data = $UserDatatableObject->datatable();

        if ($data == null) {
            return ResponseJson::error(['error'=> 'No se consiguieron usuarios registrados']);
        }
        return ResponseJson::success(['success'=> 'Consulta exitosa de los usuarios registrados'], ['users' => $data]);
    }

    public function update(UserUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $UserUpdateObject = new UserUpdateObject($request);
            if ($UserUpdateObject->getModel() != null) {
                $UserUpdateData = new UserUpdateData($UserUpdateObject->getArrayData(),$UserUpdateObject->getModel());
                $updatedFlag = $UserUpdateData->update();
                if ($updatedFlag) {
                    $message = "Usuario actualizado con exito";
                    DB::commit();
                    return ResponseJson::success(['success'=> $message]);
                } else {
                    $message = "Ocurrio un error actualizando el usuario";
                    DB::rollback();
                    return ResponseJson::error(['error'=> $message]);
                }
            }
            else{
              $message = "No se consiguio en nuestros registros el usuario a actualizar";
              DB::rollback();
              return ResponseJson::error(['error'=> $message]);
            }
        } catch (\Trowable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
