@extends('layouts.default')

@section('content')

<h1>Reset Password</h1>

<div class="row">

	<div class="col-md-4 col-md-offset-4">

		{{ Form::open(array('url' => action('RemindersController@postRemind'))) }}

			@if(Session::has('error'))
			<div class="alert alert-danger">
				{{ Session::get('error') }}
			</div>
			@endif

			@if(Session::has('status'))
			<div class="alert alert-success">
				{{ Session::get('status') }}
			</div>
			@endif

			<div class="form-group">
				{{ Form::label('email', null, array('class' => 'control-label')) }}
				{{ Form:: email('email', null, array('class' => 'form-control')) }}
		    </div>
		    <div class="form-group">
		    	{{ Form::submit('Send Reminder', array('class' => 'btn btn-primary')) }}
		    </div>
		{{ Form::close() }}

	</div>

</div>

@stop