<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Thêm tài khoản admin</title>
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/bootstrap/css/bootstrap.min.
	css') }}">
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/Font-Awesome-master/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/css/login.css') }}">
	<script src="{{ Asset('public/libs/bootstrap/js/jquery-2.1.3.min.js') }}"></script>
	<script src="{{ Asset('public/libs/bootstrap/js/bootstrap.min.js') }}"></script>
</head>
<body>
	<div class="col-md-12 wrapper">
		<div class="box-login">			
			<div class="form">
				<form action="{{ Asset('them-admin') }}" method="POST">
					<div class="form-group">
						@if (Session::has('error'))	
							<div class="alert alert-danger" style="border-radius: 0px">
								<strong>Lỗi!</strong> {{ Session::get('error') }}
							</div>
						@endif
					</div>

					<div class="form-group">
						@if (Session::has('notify'))	
							<div class="alert alert-success" style="border-radius: 0px">
								<strong>Thông báo!</strong> {{ Session::get('notify') }}
							</div>
						@endif
					</div>

					<legend>Thêm admin</legend>

					<div class="form-group">
						<label>Tài khoản:</label>
						<div class="input-group">
						    <input type="text" class="form-control" name="username" required>
					      	<div class="input-group-addon">
					      		<i class="fa fa-user-secret"></i>
					      	</div>
					    </div>
					</div>										

					<div class="form-group">
						<label>Mật khẩu:</label>
						<div class="input-group">
						    <input type="password" class="form-control" name="password" required>
					      	<div class="input-group-addon">
					      		<i class="fa fa-key"></i>
					      	</div>
					    </div>
					</div>

					<div class="form-group">
						<label>Nhập lại mật khẩu:</label>
						<div class="input-group">
						    <input type="password" class="form-control" name="password_confirm" required>
					      	<div class="input-group-addon">
					      		<i class="fa fa-key"></i>
					      	</div>
					    </div>
					</div>	
					
					<div class="action">
						<div class="half">
							<button type="submit" class="btn btn-primary btn-login">
								Tạo tài khoản
							</button>
						</div>						
					</div>
					
				</form>
			</div>
		</div>
	</div>
</body>
</html>