@extends('layouts.app')
@section('title','Admin Login')
@section('content')
<div class="mb-3">
  <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">← Back to Welcome</a>
  </div>
<div class="row justify-content-center">
 <div class="col-md-4">
  <div class="card shadow">
   <div class="card-body">
    <h4 class="mb-3">Admin Login</h4>
    <form method="POST" action="{{ route('admin.login.post') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required value="{{ old('email','admin@example.com') }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-warning w-100">Login</button>
    </form>
   </div>
  </div>
 </div>
</div>
@endsection

