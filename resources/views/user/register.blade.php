@extends('layouts.app')
@section('title','Register')
@section('content')
<div class="mb-3">
  <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">← Back to Welcome</a>
</div>
<div class="row justify-content-center">
 <div class="col-md-6">
  <div class="card shadow">
   <div class="card-body">
    <h4 class="mb-3">Register</h4>
    <form method="POST" action="{{ url('/register') }}">
     @csrf
     <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" required value="{{ old('name') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select">
          <option value="">Select gender</option>
          <option value="Male" {{ old('gender')==='Male' ? 'selected' : '' }}>Male</option>
          <option value="Female" {{ old('gender')==='Female' ? 'selected' : '' }}>Female</option>
          <option value="Other" {{ old('gender')==='Other' ? 'selected' : '' }}>Other</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">DOB</label>
        <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" max="{{ now()->toDateString() }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Contact No</label>
        <input name="contact" class="form-control" value="{{ old('contact') }}">
      </div>
      <div class="col-12">
        <label class="form-label">Address</label>
        <input name="address" class="form-control" value="{{ old('address') }}">
      </div>
     </div>
     <button class="btn btn-success mt-3">Create Account</button>
    </form>
   </div>
  </div>
 </div>
</div>
@endsection
