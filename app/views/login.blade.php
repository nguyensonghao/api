<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<style type="text/css">

		form {
			text-align: left;
			width: 200px;
    		margin-top: 50px;
		}

		body {
			text-align: center;
		}

		label, button {
			margin-top: 20px;
		}
	
	</style>
</head>
<body>
	<center>
		<h2>Đăng nhập trước khi import data vào elasticsearch</h2>
		<form action="{{ Asset('api/login') }}" method="POST">
			<label>Username: </label><br>
			<input type="text" name="email" required><br>

			<label>Password: </label><br>
			<input type="password" name="password" required><br>
	
			<button type="submit">Login</button>
		</form>
	</center>
</body>
</html>