@extends('layouts.app')
@section('title','Admin Dashboard')
@section('content')
<div class="mb-3">
  <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">← Back to Welcome</a>
</div>

<div class="row g-3">
  <div class="col-md-12">
    <div class="p-4 bg-white shadow rounded d-flex justify-content-between align-items-center">
      <div>
        <h3 class="mb-1">Admin Dashboard</h3>
        <small>Manage Registered Users & Submitted Contacts</small>
      </div>
      <form method="POST" action="{{ route('admin.logout') }}">@csrf
        <button class="btn btn-danger">Logout</button>
      </form>
    </div>
  </div>

  <div class="col-md-6">
    <div class="p-4 bg-white shadow rounded">
      <h5 class="mb-2">Registered Users</h5>
      <p class="text-muted mb-3">Total: {{ $counts['users'] }}</p>
      <a class="btn btn-primary" href="{{ route('admin.users') }}">View Registered Users</a>
      <a class="btn btn-success" href="{{ route('admin.export.users', ['format' => 'xlsx']) }}">Export XLSX</a>
    </div>
  </div>

  <div class="col-md-6">
    <div class="p-4 bg-white shadow rounded">
      <h5 class="mb-2">Submitted Contacts</h5>
      <p class="text-muted mb-3">Total: {{ $counts['contacts'] }}</p>
      <a class="btn btn-primary" href="{{ route('admin.contacts') }}">View Submitted Contacts</a>
      <a class="btn btn-success" href="{{ route('admin.export.contacts', ['format' => 'xlsx']) }}">Export XLSX</a>
    </div>
  </div>
</div>
@endsection


