@extends('layouts.app')
@section('title','User Login')
@section('content')
<div class="mb-3">
  <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">← Back to Welcome</a>
</div>
<div class="row justify-content-center">
 <div class="col-md-4">
  <div class="card shadow">
   <div class="card-body">
    <h4 class="mb-3">User Login</h4>
    <form method="POST" action="{{ url('/login') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Remember me</label>
      </div>
      <button class="btn btn-primary w-100">Login</button>
    </form>
   </div>
  </div>
 </div>
</div>
@endsection
