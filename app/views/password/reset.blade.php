@extends('layouts.default')

@section('content')

<h1>Reset Password</h1>

<div class="row">

	<div class="col-md-4 col-md-offset-4">

		{{ Form::open(array('url' => action('RemindersController@postReset'))) }}

			@if(Session::has('error'))
			<div class="alert alert-danger">
				{{ Session::get('error') }}
			</div>
			@endif

			{{Form::hidden('token', $token )}}
			<div class="form-group">
				{{ Form::label('email', null, array('class' => 'control-label')) }}
				{{ Form:: email('email', null, array('class' => 'form-control')) }}
		    </div>
			<div class="form-group">
				{{ Form::label('password', null, array('class' => 'control-label')) }}
				{{ Form:: password('password', array('class' => 'form-control')) }}
		    </div>
			<div class="form-group">
				{{ Form::label('password_confirmation', null, array('class' => 'control-label')) }}
				{{ Form:: password('password_confirmation', array('class' => 'form-control')) }}
		    </div>
		    <div class="form-group">
		    	{{ Form::submit('Reset Password', array('class' => 'btn btn-primary')) }}
		    </div>
		{{ Form::close() }}

	</div>

</div>

@stop