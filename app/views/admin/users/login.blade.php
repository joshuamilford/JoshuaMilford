@extends('layouts.default')

@section('content')

<h1>Log In</h1>

<div class="row">

	<div class="col-md-4 col-md-offset-4">
		

		{{ Form::open(array('url' => url('admin/login'))) }}

		@if($errors->has())
		<div class="alert alert-danger">
			Uh oh!
		</div>
		@endif

		<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		{{ Form::label('email', null, array('class' => 'control-label')) }}
		{{ Form::email('email', null, array('class' => 'form-control') )}}
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
		{{ Form::Submit('Log In', array('class' => 'btn btn-primary') )}}
		</div>

		{{ Form::close() }}

		<p><a href="{{ action('RemindersController@getRemind') }}">I forgot my password.</a></p>
	
	</div>

</div>

@stop