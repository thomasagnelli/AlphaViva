@extends('layouts.app')
<style>
    .login-page{background-color: transparent !important;}
</style>

@section('content-header')
<!-- Content Header (Page header) -->
<div style="display: none;" class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
              <!--<h1 class="m-0">Login</h1>-->
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Login</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<div class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    
    <div class="card-header text-center form-logo">
      <img src="/admin-lte/dist/img/AdminLTELogo.png" alt="SGR Logo" class="form-brand-image img-circle elevation-3">
      <a href="#" class=""><b>{{config('app.name')}}</b></a>
    </div>
    
    <div class="card-body">
      <p class="login-box-msg">Entre para iniciar a sessão</p>

      <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input value="{{ old('email') }}" required autofocus 
          autocomplete="email" type="email" id="email" name="email"  
          aria-invalid="@error('email') true @enderror" aria-describedby="error-email"
          class="form-control @error('email') is-invalid @enderror" placeholder="seuemail@exemplo.com">

          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>

          @error('email')
            <span id="error-email" class="error invalid-feedback">{{ $message }}</span>
          @enderror
        </div>

        <div class="input-group mb-3">
          <input required autocomplete="current-password" type="password" 
          id="password" name="password"
          class="form-control @error('password') is-invalid @enderror"
          aria-invalid="@error('password') true @enderror" aria-describedby="error-password"
          placeholder="Sua senha">

          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>

         @error('password')
            <span id="error-password" class="error invalid-feedback">{{ $message }}</span>
         @enderror
        
        </div>

        <div class="row">
          <div class="col-6">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
              <label for="remember">
                Lembrar de mim
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center row" style="display: none;">
        <p class="login-box-msg col-12 text-center">Entre com</p>

        <div class="row col-12">
          <a href="#" class="btn  btn-primary col-5 offset-1">
            <i class="fab fa-facebook"></i> Facebook
          </a>
          <a href="#" class="btn  btn-danger col-5 offset-1">
            <i class="fab fa-google"></i> Google
          </a>
        </div>
      </div>
      <!-- /.social-auth-links -->

    @if (Route::has('password.request'))
      <p class="mb-1">
        <a href="{{ route('password.request') }}">Recuperar minha Senha</a>
      </p>
    @endif
    @if (Route::has('register'))
      <p class="mb-0">
        <a href="register" class="text-center">Registrar</a>
      </p>
    @endif
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
</div>
@endsection
