@extends('layouts.app')
@section('title','My Contacts')
@section('content')
<div class="mb-3">
  <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">← Back to Welcome</a>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>My Contacts</h3>
  <a class="btn btn-success" href="{{ route('contacts.create') }}">Add Contact</a>
</div>

@if($contacts->isEmpty())
  <div class="alert alert-info">No contacts yet.</div>
@else
  <table class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Contact</th><th>Added</th>
      </tr>
    </thead>
    <tbody>
      @foreach($contacts as $c)
      <tr>
        <td>{{ $c->id }}</td>
        <td>{{ $c->name }}</td>
        <td>{{ $c->email }}</td>
        <td>{{ $c->contact }}</td>
        <td>{{ $c->created_at->format('Y-m-d H:i') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endif
@endsection
