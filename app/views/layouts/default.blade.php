<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title></title>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
	</head>
	<body>
		<div class="navbar navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">CMS</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="{{ url('admin/user') }}">Users</a></li>
						@if(Auth::check())
						<li><a href="{{ url('admin/logout') }}">Log Out</a></li>
						@endif
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
		
		<div class="container-fluid">
		
		@if(Session::has('message'))
			@foreach(Session::get('message') as $type=>$message)
			<div class="alert alert-{{ $type }}">
				{{ $message }}
			</div>
			@endforeach
		@endif

		@yield('content')
		
		</div>
		
		<script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	</body>
</html>