@extends('layouts.default')

@section('content')

<h1>Manage Pages</h1>
<p><a href="{{ url('admin/page') }}">All Pages</a></p>

<div class="row">

	<div class="col-md-4 col-md-offset-4">
		

		{{ Form::open($form) }}

		@if($errors->has())
		<div class="alert alert-danger">
			Uh oh!
		</div>
		@endif

		<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
		{{ Form::label('title', null, array('class' => 'control-label')) }}
		{{ Form::text('title', $page->title, array('class' => 'form-control') )}}
		@if($errors->has('title'))
		<span class="help-block">{{ $errors->first('title') }}</span>
		@endif
		</div>

		<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
		{{ Form::label('content', null, array('class' => 'control-label')) }}
		{{ Form::textarea('content', $page->content, array('class' => 'form-control') )}}
		@if($errors->has('content'))
		<span class="help-block">{{ $errors->first('content') }}</span>
		@endif
		</div>

		<div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
		{{ Form::label('slug', null, array('class' => 'control-label')) }}
		{{ Form::text('slug', $page->slug, array('class' => 'form-control') )}}
		@if($errors->has('slug'))
		<span class="help-block">{{ $errors->first('slug') }}</span>
		@endif
		</div>

		<div class="form-group">
		{{ Form::label('parent_id', 'Parent', array('class' => 'control-label')) }}
		{{ Form::select('parent_id', array('') + $parents, $page->parent_id, array('class' => 'form-control') )}}
		</div>


		@foreach($categories as $cat)

		<div class="checkbox">
			<label for="cat_{{ $cat->id }}">
				{{ Form::checkbox('categories[]', $cat->id, in_array($cat->id, $page_cats), array('id' => 'cat_' . $cat->id)) }}
				{{ $cat->name }}
			</label>
		</div>

		@endforeach

		<div class="form-group">
		{{ Form::Submit('Save', array('class' => 'btn btn-primary') )}}
		</div>

		{{ Form::close() }}

		@if($page->id)

		{{ Form::open(array('url' => url('admin/page/' . $page->id), 'method' => 'DELETE')) }}
		{{ Form::Submit('Delete', array('class' => 'btn btn-danger pull-right')) }}
		{{ Form::close() }}

		@endif
	
	</div>

</div>

@stop