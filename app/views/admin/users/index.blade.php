@extends('layouts.default')

@section('content')
<h1>Manage Users</h1>
<p><a href="{{ url('admin/user/create') }}" class="btn btn-primary">New User</a></p>
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>Id</td>
			<td>Email</td>
			<td>Added</td>
		</tr>
	</thead>
	<tbody>
		@foreach($users as $user)
		<tr>
			<td>{{ $user->id }}</td>
			<td><a href="{{ url('admin/user/' . $user->id . '/edit') }}">{{ $user->email }}</a></td>
			<td>{{ $user->created_at }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

@stop