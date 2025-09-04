@extends('layouts.app')
@section('title','Welcome')
@section('content')
<div class="text-center py-5">
  <h1 class="mb-3">Welcome Folks!</h1>
  <p class="lead">Choose an option to continue</p>
  <div class="d-flex justify-content-center gap-3 mt-3">
    <a class="btn btn-primary" href="{{ route('admin.login') }}">Admin</a>
    <a class="btn btn-success" href="{{ route('register') }}">Register</a>
    <a class="btn btn-secondary" href="{{ route('login') }}">User Login</a>
  </div>
</div>
@endsection
