<?php
use Illuminate\Auth\Authenticatable;
?>
<html>
<body>
	{{-- Load any assets here --}}
	{!! Html::script('assets/js/jquery-1.11.3.min.js') !!}
	{{-- Create a variable for the crsf token for auto generated forms --}}
	<script>window.csrfToken = '<?php echo csrf_token(); ?>';</script>
	{!! Html::script('assets/js/method.js') !!}
	
	<h1>Welcome to the message board!</h1>
	
	{{-- Display the logged in user --}}
	@if(Auth::check())
		<h2>{{ Auth::user()->name }} is singed in!</h2>
	@endif
	
	{{-- Load any extended 'content' from other views --}}
	@yield('content')
</body>
</html>