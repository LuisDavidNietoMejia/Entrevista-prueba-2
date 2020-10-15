@extends('layouts.app')

@section('content')

<div class="row">
  <div class="container">
    <document-index :user_id="{!! Auth::user()->id !!}"></document-index>
  </div>
</div>

@endsection
