@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Panel de control </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Administracion de usuarios</h5>
                                <p class="card-text">Administra a tu usuarios registrados en tu sistema</p>
                                <br>
                                <a href="{{ route('user.index') }}" class="btn btn-primary">Ir</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Administracion de documentos</h5>
                                <p class="card-text">Gestiona los documentos relacionados a tus usuarios registrados en tu sistema</p>
                                <br>
                                <a href="{{ route('document.index')}}" class="btn btn-primary">Ir</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
