@extends('layouts.app')

@section('content')
  <usuarios :Auth="{{ json_encode(Auth::user()) }}" />
@endsection
