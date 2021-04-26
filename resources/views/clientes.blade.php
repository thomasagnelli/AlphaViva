@extends('layouts.app')

@section('content')
  <clientes :Auth="{{ json_encode(Auth::user()) }}" />
@endsection
