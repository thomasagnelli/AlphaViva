@extends('layouts.app')
@section('content')
  <dashboard :Auth="{{ json_encode(Auth::user()) }}"/>
@endsection
