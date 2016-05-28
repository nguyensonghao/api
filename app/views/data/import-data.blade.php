@extends('data.layout')

@section('content')

	<div class="col-md-12 content-import-data">
		<form action="{{ Asset('them-dulieu-json') }}" method="POST" role="form" enctype="multipart/form-data" name="formImport" onsubmit="return showAlertImport()">
			<legend>Import cơ sở dữ liệu</legend>
			<p class="title">Chọn file cần import</p>
			<input type="file" name="file" accept=".json">
			<p class="note">Chú ý: file phải ở dạng chuẩn cấu trúc json</p>
			
			@if (Session::has('error'))
				<div class="alert alert-danger">
					<strong>Lỗi!</strong> {{ Session::get('error') }}
				</div>
			@endif

			@if (Session::has('notify'))
				<div class="alert alert-success">
					<strong>Thông báo!</strong> {{ Session::get('notify') }}
				</div>
			@endif
			
			<h3>Quá trình import dữ liệu diễn ra khá lâu. Yêu cầu chỉ <b>click 1 lần</b> vào nút <b>thêm dữ liệu</b> và chờ đợi</h3><hr>
			
			@if (!is_null($importStatus) && $importStatus->status == 1)
				<div class="alert alert-danger">
					<p>Đang diễn ra tiến trình import dữ liệu, cân nhắc thêm dữ liệu sẽ dễ dẫn đến xung đột</p>
					<p>Thông tin khóa học:</p>
					<p>Id: {{ $importStatus->id }}</p>
					<p>Name:  {{ $importStatus->name }}</p>
				</div>
				<button type="submit" class="btn btn-danger disabled" disabled="true">Thêm dữ liệu</button>
			@else
				<button type="submit" class="btn btn-danger">Thêm dữ liệu</button>
			@endif

			<div class="alert alert-danger show-importing">
				Đang import ...
			</div>
		</form>		
	</div>

	<script>
		function showAlertImport () {
			$('.show-importing').css('display', 'block');
		}
	</script>

@endsection

<style type="text/css">
	
	.content-import-data {
		padding: 16px;
		min-height: 500px;
	}

	.content-import-data .title {
		font-size: 18px;
    	margin-bottom: 20px;
	}

	.content-import-data .note {
		font-size: 14px;
		font-style: italic;
		margin-top: 20px;
	}

	.content-import-data .btn-danger {
		border-radius: 0px !important;
	}

	.content-import-data .loadding {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100vw;
		height: 100vh;
		z-index: 1000;
		text-align: center;
		padding-top: 50%;
		font-size: 30px;
		background: rgba(0, 0, 0, 0.6);
		color: white;
	}

	.content-import-data .show-importing {
		display: none;
		margin-top: 20px;
	}

</style>