<!DOCTYPE html>
<html>
<head>
	<title>Demo Elastic Search</title>
	<link rel="stylesheet" href="{{ Asset('public/libs/bootstrap/css/bootstrap.css') }}">
	<script src="{{ Asset('public/libs/bootstrap/js/jquery-2.1.3.min.js') }}"></script>
	<script src="{{ Asset('public/libs/bootstrap/js/bootstrap.min.js') }}"></script>
	<style type="text/css">
		body {
			background: #eee;
		}

		.wrapper {
			margin-top: 50px !important;
			box-shadow: 0 2px 4px rgba(0,0,0,0.2),0 -1px 0px rgba(0,0,0,0.02);
			background: white;
			padding-bottom: 20px;
		}

		h1 {
			text-align: center;
			margin-bottom: 50px;
		}

		.form-control, .btn {
			border-radius: 0;
		}

		.btn-search {
			margin-top: 30px;
		}

		.alert-error {
			display: none;
			margin-top: 10px;
		}

		.result {
			margin-top: 20px;
			display: none;
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function () {
			// $('input[name="query"]').keypress({
			// 	var condition = $('input[name="query"]').val();
			// 	if (condition != '') 
			// 		$('.alert-error').fadeOut(100);
			// })

			$('.btn-search').click(function () {
				var query = $('input[name="query"]').val();
				if (query == '') {
					$('.alert-error').fadeIn(100);
				} else {
					$('.alert-error').fadeOut(100);
					var type = $('input[name="type"]').filter(':checked' ).val();

					if (type == 'kanji') {
						searchKanji(query, 10);
					} else if (type == 'jaen') {
						searhJaen(query);
					} else {
						searchExam(query);
					}
				}
			})

			function searchKanji (query, numberSearch) {
				$.ajax({
					url     : 'api/kanji/' + query + '/' + numberSearch,
					type    : 'post',
					success : function (data) {
						showResult(data);
					}
				})
			}

			function searhJaen (query) {
				$.ajax({
					url     : 'api/word/' + query,
					type    : 'post',
					success : function (data) {
						showResult(data);
					}
				})
			}

			function searchExam (query) {
				$.ajax({
					url     : 'api/example/' + query,
					type    : 'post',
					success : function (data) {
						showResult(data);
					}
				})
			}

			function showResult (result) {
				console.log(result);
				$('.result').css('display', 'block');
				$('.status').html(result.status);
				if (result.result != null)
					$('.number-result').html(result.result.length);					
			}
		})
	</script>
</head>
<body>
	<div class="wrapper col-md-8 col-md-offset-2">
		<h1>Demo search sử dụng elastic</h1>
		<div class="col-md-4">
			<h4>Hãy chọn kiểu tìm kiếm</h4>
			<div class="radio">
				<label>
					<input type="radio" name="type" checked="checked" value="kanji">
					Tra cứu Kanji
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="type" value="jaen">
					Tra cứu Jaen
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="type" value="exam">
					Tra cứu Example
				</label>
			</div>
		</div>

		<div class="col-md-8">
			<input type="text" name="query" class="form-control" placeholder="Nhập từ tìm kiếm">
			<button type="button" class="btn btn-danger btn-search">Tìm kiếm</button>
			<div class="alert alert-danger alert-error">
				<strong>Thông báo!</strong> hãy điền thông tin vào ô tìm kiếm
			</div>
			<div class="result">
				<p><b>	
					Mã trạng thái : 
					<span class="status"></span>
				</b></p>

				<p><b>
					Số lượng kết quả tương ứng : 
					<span class="number-result"></span>
				</b></p>
				<p>Xem log để xem chi tiết kết quả</p>
			</div>
		</div>
	</div>
</body>
</html>