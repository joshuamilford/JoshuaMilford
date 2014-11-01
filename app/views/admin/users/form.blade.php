@extends('layouts.default')

@section('content')

<h1>Manage Users</h1>
<p><a href="{{ url('admin/user') }}">All Users</a></p>

<div class="row">

	<div class="col-md-4 col-md-offset-4">
		

		{{ Form::open($form) }}

		@if($errors->has())
		<div class="alert alert-danger">
			Uh oh!
		</div>
		@endif

		<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		{{ Form::label('email', null, array('class' => 'control-label')) }}
		{{ Form::email('email', $user->email, array('class' => 'form-control') )}}
		@if($errors->has('email'))
		<span class="help-block">{{ $errors->first('email') }}</span>
		@endif
		</div>

		<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
		{{ Form::label('password', null, array('class' => 'control-label')) }}
		{{ Form::password('password', array('class' => 'form-control') )}}
		@if($errors->has('password'))
		<span class="help-block">{{ $errors->first('password') }}</span>
		@endif
		</div>

		<div class="form-group">
		{{ Form::Submit('Save', array('class' => 'btn btn-primary') )}}
		</div>

		{{ Form::close() }}

		@if($user->id)

		{{ Form::open(array('url' => url('admin/user/' . $user->id), 'method' => 'DELETE')) }}
		{{ Form::Submit('Delete', array('class' => 'btn btn-danger pull-right')) }}
		{{ Form::close() }}

		@endif
	
	</div>

</div>

@stop