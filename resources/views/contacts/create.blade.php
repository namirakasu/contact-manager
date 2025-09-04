@extends('layouts.app')
@section('title','Add Contact')
@section('content')
<div class="mb-3">
  <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">← Back to Welcome</a>
</div>
<div class="row justify-content-center">
 <div class="col-md-6">
  <div class="card shadow">
   <div class="card-body">
    <h4 class="mb-3">Add Contact</h4>
    <form method="POST" action="{{ route('contacts.store') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" required value="{{ old('name') }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Contact</label>
        <input name="contact" class="form-control" required value="{{ old('contact') }}">
      </div>
      <button class="btn btn-success">Save</button>
    </form>
   </div>
  </div>
 </div>
</div>
@endsection
