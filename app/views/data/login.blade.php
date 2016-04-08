<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Đăng nhập hệ thống</title>
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
			<div class="banner">
				<i class="fa fa-lock"></i> Đăng nhập hệ thống
			</div>
			<div class="introduction not-mobile">
				<p class="title">Học tự vựng thông minh Minder</p>
				<p>- Từ vựng của các giáo trình tiếng Nhật</p>
				<p>- Từ vựng chuyên ngành: IT, Y tế, Xây dựng...</p>
				<p>- Từ vựng đời sống..</p>
			</div>

			<div class="form">
				<form action="{{ Asset('dang-nhap') }}" method="POST">
					<div class="form-group">
						@if (Session::has('error'))	
							<div class="alert alert-danger" style="border-radius: 0px">
								<strong>Lỗi!</strong> {{ Session::get('error') }}
							</div>
						@endif
					</div>

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
					
					<div class="action">
						<div class="half">
							<button type="submit" class="btn btn-primary btn-login">
								Đăng nhập
							</button>
						</div>
						<div class="half forget-password">
							<a class="btn btn-link" href="#">Quên mật khẩu</a>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</body>
</html>