@extends('layouts.app')
@section('title','Submitted Contacts')
@section('content')
<div class="mb-3">
  <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
</div>

<h2 class="mb-3">Submitted Contacts</h2>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-6">
    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search name, email, contact...">
  </div>
  <div class="col-md-2">
    <button class="btn btn-primary w-100">Search</button>
  </div>
  <div class="col-md-2">
    <a href="{{ route('admin.contacts') }}" class="btn btn-secondary w-100">Reset</a> 
  </div>
</form>
    //export contacts to csv or excel
<div class="mb-3">
  <a class="btn btn-success me-2" href="{{ route('admin.export.contacts', ['format' => 'xlsx']) }}">Export Contacts to Excel (XLSX)</a>
  <a class="btn btn-outline-secondary" href="{{ route('admin.export.contacts', ['format' => 'csv']) }}">Export Contacts to CSV</a>
</div>

<div class="table-responsive">
  <table class="table table-bordered align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
      @forelse($contacts as $c)
      <tr>
        <td>{{ $c->id }}</td>
        <td>{{ optional($c->user)->name }}</td>
        <td>{{ $c->name }}</td>
        <td>{{ $c->email }}</td>
        <td>{{ $c->contact }}</td>
        <td>{{ $c->created_at->format('Y-m-d H:i') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="text-center">No contacts found</td>
      </tr>
      @endforelse
    </tbody>
  </table>
  </div>
@endsection


