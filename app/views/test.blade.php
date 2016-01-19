<!DOCTYPE html>
<html ng-app="app">
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/bootstrap/css/bootstrap.css') }}">
	<style type="text/css">
		.navbar-brand {
			background: #337ab7;
    		color: white;
		}
	</style>
</head>
<body>
<div class="navbar col-md-8 col-md-offset-2">
	<a class="navbar-brand" href="#">Demo acount Api</a>
	<ul class="nav navbar-nav">
		<li class="active">
			<a href="#home">Home</a>
		</li>
		<li class="active">
			<a href="#login" ng-if="user == null">Login</a>
		</li>
		<li class="active">
			<a href="#login" ng-if="user != null" ng-click="logout()">
				Logout
			</a>
		</li>
		<li>
			<a href="#register">Register</a>
		</li>
	</ul>
</div>

<ui-view name="main"></ui-view>
<script type="text/javascript" src="{{ Asset('public/libs/angular/angular.js') }}"></script>
<script type="text/javascript" src="{{ Asset('public/libs/angular-ui-router/release/angular-
ui-router.js') }}"></script>
<script type="text/javascript" src="{{ Asset('public/libs/app.js') }}"></script>
<script type="text/javascript" src="{{ Asset('public/libs/login.js') }}"></script>
<script type="text/javascript" src="{{ Asset('public/libs/register.js') }}"></script>
<script type="text/javascript" src="{{ Asset('public/libs/home.js') }}"></script>
<script type="text/javascript" src="{{ Asset('public/libs/localServ.js') }}"></script>
</body>
</html>