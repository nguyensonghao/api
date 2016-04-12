
@extends('data.layout')

@section('content')
	
	<div class="row list-image">
		@if (count($listWord) == 0)
			<div class="alert alert-danger">				
				Chưa có từ nào
			</div>
		@else
			@foreach($listWord as $key=>$value)
			<div class="col-md-3">
				<div class="panel">
					<div class="panel-heading">
						<h3 class="panel-title">{{ $value->word }}({{$value->mean}})</h3>
					</div>
					<div class="panel-body">
						<a class="thumbnail">
							<img src="{{ Asset('public/AllData') .'/'. $value->course_name. '/' . $value->id_course . '/images/words/' . $value->id_word . '.jpg'}}">
						</a>
						<div class="btn-group">
							<button type="button" class="btn btn-default" onclick='showImage({{$value->id}}, "{{ $value->word }}", "{{ $value->mean }}")'>
								<span class="glyphicon glyphicon-eye-open"></span>
								Xem
							</button>
							<button type="button" class="btn btn-danger" onclick='fixMean({{$value->id}}, "{{ $value->word }}", "{{ $value->mean }}", "{{ $value->phonetic }}", "{{ $value->des }}")'>
								<span class="glyphicon glyphicon glyphicon-pencil"></span> 
								Sửa
							</button>
						</div>
					</div>				
				</div>
			</div>
			@endforeach
		@endif
	</div>	

	{{ $listWord->links() }}

	<div class="modal fade" id="modal-show-image">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body col-md-12">
					<div class="loadding">
						Đang tải ....
					</div>

					<div class="result">

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>					
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-fix-mean">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Sửa lỗi</h4>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>					
				</div>
			</div>
		</div>
	</div>

	<script>
		var listImage = [];
		var indexPage = 0;
		var idWord;

		var showImage = function (id, word, mean) {		
			indexPage = 1;
			idWord = id;	
			$('#modal-show-image .modal-title').html('Ảnh cho từ ' + word + '('+ mean +')');
			var str = '';
			$('#modal-show-image').modal('show');
			$('#modal-show-image .loadding').css('display', 'block');
			$('#modal-show-image .modal-body .result').html('');
			listImage = [];
			$.ajax({
				url: '<?php echo Asset("lay-danh-sach-anh") ?>',
				type: 'post',
				data: {id : id},
				success: function (data) {					
					var listUrl = data.url;
					if (listUrl == null) {
						$('#modal-show-image').modal('hide');
						alert('Có lỗi hệ thống xảy ra');
					} else {
						for (var i = 0; i < listUrl.length; i++) {						
							str += '<div class="col-md-3"><div class="box"><img src="'+ listUrl[i].url +'" height="300px" width="100%"><button onclick="downloadImage('+ data.id +', '+ i +')" class="btn btn-primary">Chọn</button></div></div>';
							listImage.push({
								index: i,
								url : listUrl[i].url
							})
						}					
						$('#modal-show-image .loadding').css('display', 'none');
						$('#modal-show-image .modal-body .result').html(str);
					}					
				},
				error: function () {
					$('#modal-show-image').modal('hide');
					alert('Có lỗi hệ thống xảy ra');
				}
			})
		}

		var downloadImage = function (id, index) {
			$('.cover').css('display', 'block');
			for (var i = 0; i < listImage.length; i++) {
				if (listImage[i].index == index) {					
					$.ajax({
						url: "<?php echo Asset('tai-anh-ve') ?>",
						type: 'post',
						data : {id: id, url: listImage[i].url},
						success: function (data) {							
							$('.cover').css('display', 'none');
							if (data.status == 200) {
								$('#modal-show-image').modal('hide');
								location.reload();
							} else {
								$('#modal-show-image').modal('hide');
								alert('Có lỗi hệ thống xảy ra');
							}
						},
						error: function () {
							$('.cover').css('display', 'none');
							$('#modal-show-image').modal('hide');
							alert('Có lỗi hệ thống xảy ra');
						}						
					})
					break;
				}
			}
		}

		var fixMean = function (id, word, mean, phonectic, des) {			
			var str = '<p>Sửa nghĩa cho từ: '+word+'</p>';
			str += '<input type="text" name="word" class="form-control name-word" value="'+word+'"><p></p>';
			str += '<input type="text" name="mean" class="form-control mean-word" value="'+mean+'"><p></p>';
			str += '<input type="text" name="phonectic" class="form-control phonectic-word" value="'+phonectic+'" placeholder="Phiên âm"><p></p>';
			str += '<textarea class="des-word form-control" placeholder="Miêu tả">'+des+'</textarea><hr>';
			str += '<button type="button" class="btn btn-primary" onclick="updateMean('+id+')">Sửa</button>';
			$('#modal-fix-mean .modal-body').html(str);
			$('#modal-fix-mean').modal('show');
		}

		var updateMean = function (id) {			
			var name = $('#modal-fix-mean .modal-body .name-word').val();
			var mean = $('#modal-fix-mean .modal-body .mean-word').val();
			var phonectic = $('#modal-fix-mean .modal-body .phonectic-word').val();
			var des = $('#modal-fix-mean .modal-body .des-word').val();
			$.ajax({
				url : '<?php echo Asset("sua-nghia") ?>',
				type : 'post',
				data : {id : id, mean : mean, phonectic : phonectic, word : name, des: des},
				success : function (data) {
					if (data.status == 200) {
						$('#modal-fix-mean').modal('hide');
						location.reload();
					} else {
						$('#modal-fix-mean').modal('hide');
						alert('Có lỗi trong quá trình xử lý');
					}
				},
				error : function () {
					$('#modal-fix-mean').modal('hide');
					alert('Có lỗi trong quá trình xử lý');
				}
			})
		}


		$('.btn-load-more').click(function () {
			$('.cover').css('display', 'block');
			++indexPage;
			$.ajax({
				url: '<?php echo Asset("them-anh") ?>',
				type: 'post',
				data: {start : indexPage * 20, id : idWord},
				success: function (data) {					
					$('.cover').css('display', 'none');
					var listUrl = data.url;
					if (listUrl == null) {
						$('#modal-show-image').modal('hide');
						alert('Có lỗi hệ thống xảy ra');
					} else {
						var str = '';
						for (var i = 0; i < listUrl.length; i++) {
							var index = i + indexPage * 20 ;
							str += '<div class="col-md-3"><div class="box"><img src="'+ listUrl[i].url +'" height="300px" width="100%"><button onclick="downloadImage('+ data.id +', '+ index +')" class="btn btn-primary">Chọn</button></div></div>';
							listImage.push({
								index: i + indexPage * 20,
								url : listUrl[i].url
							})
						}					
						$('#modal-show-image .loadding').css('display', 'none');
						$('#modal-show-image .modal-body .result').append(str);
					}					
				},
				error: function () {
					$('.cover').css('display', 'none');					
					alert('Có lỗi hệ thống xảy ra');
				}
			})
		})
	</script>

@endsection