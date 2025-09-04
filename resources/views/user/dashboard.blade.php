@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="mb-3">
  <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">← Back to Welcome</a>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="p-4 bg-white shadow rounded">
      <h3 class="mb-3">User Dashboard</h3>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="{{ route('contacts.index') }}">My Contacts</a>
        <a class="btn btn-outline-success" href="{{ route('contacts.create') }}">Add Contact</a>
        <a class="btn btn-outline-secondary" href="{{ route('profile.edit') }}">Update Profile</a>
      </div>
    </div>
  </div>
</div>
@endsection
