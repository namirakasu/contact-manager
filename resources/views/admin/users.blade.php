@extends('layouts.app')
@section('title','Registered Users')
@section('content')
<div class="mb-3">
  <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
</div>

<h2 class="mb-3">Registered Users</h2>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-4">
    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search name, email, contact...">
  </div>
  <div class="col-md-3">
    <select name="gender" class="form-select">
      <option value="">All Genders</option>
      <option value="male" @selected($gender==='male')>Male</option>
      <option value="female" @selected($gender==='female')>Female</option>
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-primary w-100">Search</button>
  </div>
  <div class="col-md-2">
    <a href="{{ route('admin.users') }}" class="btn btn-secondary w-100">Reset</a>
  </div>
</form>

<div class="mb-3">
  <a class="btn btn-success me-2" href="{{ route('admin.export.users', ['format' => 'csv']) }}">Export Users to CSV</a>
  <a class="btn btn-outline-success" href="{{ route('admin.export.users', ['format' => 'xlsx']) }}">Export Users to Excel</a>
  </div>

<div class="table-responsive">
  <table class="table table-bordered align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Gender</th>
        <th>Address</th>
        <th>Contact</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
      <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->gender }}</td>
        <td>{{ $user->address }}</td>
        <td>{{ $user->contact }}</td>
        <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center">No users found</td>
      </tr>
      @endforelse
    </tbody>
  </table>
  </div>
@endsection


