@extends('layouts.app')

@section('content')
  <unidades  :Auth="{{ json_encode(Auth::user()) }}" />
@endsection
