@extends('layouts.default')

@section('content')
<h1>Manage Pages</h1>
<p><a href="{{ url('admin/page/create') }}" class="btn btn-primary">New Page</a></p>

{{ $sitemap }}

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>Id</td>
			<td>Title</td>
			<td>Slug</td>
			<td>Added</td>
		</tr>
	</thead>
	<tbody>
		@foreach($pages as $page)
		<tr>
			<td>{{ $page->id }}</td>
			<td><a href="{{ url('admin/page/' . $page->id . '/edit') }}">{{ $page->title }}</a></td>
			<td>{{ $page->slug }}</td>
			<td>{{ $page->created_at }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

@stop