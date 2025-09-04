@extends('layouts.app')
@section('title','Update Profile')
@section('content')
<div class="mb-3">
  <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">← Back</a>
</div>
<div class="row justify-content-center">
 <div class="col-md-8">
  <div class="card shadow">
   <div class="card-body">
    <h4 class="mb-3">Update Profile</h4>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="return_to" value="{{ url()->previous() }}">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required>
        </div>
        
        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select">
            <option value="">Select gender</option>
            <option value="Male" {{ old('gender',$user->gender)==='Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender',$user->gender)==='Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('gender',$user->gender)==='Other' ? 'selected' : '' }}>Other</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">DOB</label>
          <input type="date" name="dob" class="form-control" value="{{ old('dob',$user->dob) }}" max="{{ now()->toDateString() }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Contact No</label>
          <input name="contact" class="form-control" value="{{ old('contact',$user->contact) }}">
        </div>
        <div class="col-12">
          <label class="form-label">Address</label>
          <input name="address" class="form-control" value="{{ old('address',$user->address) }}">
        </div>
        <div class="col-12">
          <label class="form-label">Profile Picture</label>
          <input type="file" name="profile_pic" class="form-control">
          @if($user->profile_pic)
            <img class="mt-2 rounded" src="{{ asset('storage/'.$user->profile_pic) }}" width="100">
          @endif
        </div>
      </div>
      <button class="btn btn-success mt-3">Save Changes</button>
    </form>
   </div>
  </div>
 </div>
</div>
@endsection
