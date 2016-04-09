<!DOCTYPE html>
<html>
<head>
	<title>Trang quản lý dữ liệu MWord</title>	
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/bootstrap/css/bootstrap.min.css') }}">	
	<link rel="stylesheet" type="text/css" href="{{ Asset('public/libs/css/style.css') }}">
	<script src="{{ Asset('public/libs/bootstrap/js/jquery-2.1.3.min.js') }}"></script>
	<script src="{{ Asset('public/libs/bootstrap/js/bootstrap.min.js') }}"></script>
</head>
<body>
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Mazii Admin</a>
			</div>
	
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">					
					<li class="dropdown {{ WordController::actionActiveMenu(101000000) }}">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tiếng Anh <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li>
								<a href="{{ Asset('danh-sach-anh-da-duyet/101000000') }}">
									Danh sách ảnh đã duyệt
								</a>
							</li>
							<li>
								<a href="{{ Asset('danh-sach-anh-chua-duyet/101000001/all') }}">
									Danh sách ảnh chưa duyệt
								</a>
							</li>
						</ul>
					</li>
					<li class="dropdown {{ WordController::actionActiveMenu(102000000) }}">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tiếng Trung <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li>
								<a href="{{ Asset('danh-sach-anh-da-duyet/102000000') }}">
									Danh sách ảnh đã duyệt
								</a>
							</li>
							<li>
								<a href="{{ Asset('danh-sach-anh-chua-duyet/102000000/all') }}">
									Danh sách ảnh chưa duyệt
								</a>
							</li>
						</ul>
					</li>
					<li class="dropdown {{ WordController::actionActiveMenu(103000000) }}">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tiếng Hàn <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li>
								<a href="{{ Asset('danh-sach-anh-da-duyet/103000002') }}">
									Danh sách ảnh đã duyệt
								</a>
							</li>
							<li>
								<a href="{{ Asset('danh-sach-anh-chua-duyet/103000000/all') }}">
									Danh sách ảnh chưa duyệt
								</a>
							</li>
						</ul>
					</li>
					<li class="dropdown {{ WordController::actionActiveMenu(104000000) }}">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tiếng Nhật <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li>
								<a href="{{ Asset('danh-sach-anh-da-duyet/104000000') }}">
									Danh sách ảnh đã duyệt
								</a>
							</li>
							<li>
								<a href="{{ Asset('danh-sach-anh-chua-duyet/104000000/all') }}">
									Danh sách ảnh chưa duyệt
								</a>
							</li>
						</ul>
					</li>
					@if (Auth::user()->active == 10) 						
						<li class=" dropdown{{ WordController::actionActiveMenu('xuat-du-lieu') }}">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Xuất dữ liệu <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li>
									<a href="{{ Asset('xuat-du-lieu-khoa-hoc') }}">
										Khóa học
									</a>
								</li>
								<li>
									<a href="{{ Asset('xuat-du-lieu-topic') }}">
										Topic
									</a>
								</li>
							</ul>
						</li>
					@endif
				</ul>
				<ul class="nav navbar-nav navbar-right">					
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->username }} <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="{{ Asset('dang-xuat') }}">Đăng xuất</a></li>							
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		@yield('content')
	</div>

	<div class="cover">
		<div class="loading">Đang tải ảnh.....</div>
	</div>
</body>
</html>