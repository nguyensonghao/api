
@extends('data.layout')

@section('content')
	
	<div class="row list-image">
		<div class="col-md-12 select-subject">
			<form class="form-inline">
				<div class="form-group">
					<label>Khóa học:</label>
					<select class="form-control" onchange="window.location.href = this.value">
						<option @if (Session::get('select_course') == 'all') selected @endif value="{{ Asset('danh-sach-anh-chua-duyet/'. ((int)(Request::segment(2) / 1000)) * 1000 .'/all') }}">
							Tất cả							
						</option>
						@foreach ($listCourse as $value)
							<option value="{{ Asset('danh-sach-anh-chua-duyet') . '/' . $value->id . '/all' }}" @if (Session::get('select_course') == $value->id) selected @endif>
								{{ $value->name }}
							</option>
						@endforeach						
					</select>
				</div>
				<div class="form-group">
					<label style="margin-left: 20px">Topic:</label>
					<select class="form-control" onchange="window.location.href = this.value">
						<option value="{{ Asset('danh-sach-anh-chua-duyet') . '/' . Request::segment(2) . '/all'}}" @if (Session::get('select_subject') == 'all') selected @endif>
							Tất cả							
						</option>
						@foreach ($listSubject as $value)
							<option value="{{ Asset('danh-sach-anh-chua-duyet') . '/' . Request::segment(2) . '/' . $value->id }}" @if (Session::get('select_subject') == $value->id) selected @endif>
								@if ($value->name != null && $value->name != '')
									{{ $value->name }}
								@else
									{{ $value->mean }}
								@endif								
							</option>
						@endforeach						
					</select>
				</div>				
			</form>
		</div>
		@foreach($listWord as $key=>$value)
		<div class="col-md-3">
			<div class="panel">
				<div class="panel-heading panel-title">
					<h3>{{ $value->word }}({{$value->mean}})</h3>
					@if ($value->phonetic != null && $value->phonetic != '')
						<i>Phiên âm: {{ $value->phonetic }}</i>
					@else 
						<i>Phiên âm: Trống</i>
					@endif
				</div>
				<div class="panel-body">
					<a class="thumbnail">
						<img src="{{ Asset('public/AllData') .'/'. $value->course_name. '/' . $value->id_course . '/images/words/' . $value->id_word . '.jpg'}}">
					</a>
					<p>ID: {{ $value->id_word }}</p>
					<div class="btn-group">
						<button class="btn btn-sm btn-primary btn-{{$value->id}}" onclick="excutedImage({{$value->id}})">
							<span class="glyphicon glyphicon-ok"></span> 
							Xong
						</button>
						<button type="button" class="btn btn-sm btn-default btn-show-image-{{ $value->id }}" onclick='showImage({{$value->id}})' word="{{ $value->word }}" mean="{{ $value->mean }}">
							<span class="glyphicon glyphicon-eye-open"></span> 
							Xem
						</button>
						<button type="button" class="btn btn-sm btn-danger btn-fix-word-{{ $value->id }}" onclick='fixMean({{$value->id}})' word="{{ $value->word }}" mean="{{ $value->mean }}" phonetic="{{ $value->phonetic }}" des="{{ $value->des }}">
							<span class="glyphicon glyphicon glyphicon-pencil"></span> 
							Sửa
						</button>
						<button type="button" class="btn btn-sm btn-default btn-sound btn-sound-{{ $value->id }}" urlSound="{{ Asset('public/AllData') .'/'. $value->course_name. '/' . $value->id_course . '/audios/' . $value->id_word . '.mp3'}}" onclick="soundAudio({{ $value->id }})">
							<span class="glyphicon glyphicon-volume-up"></span>
						</button>
					</div>
				</div>				
			</div>
		</div>
		@endforeach
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
					<div class="btn-group">
						<a class="btn btn-danger btn-search-more" role="button" data-toggle="collapse" href="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
						  Tìm ảnh mới
						</a>
						<button type="button" class="btn btn-success btn-upload-more" role="button" data-toggle="collapse" href="#collapseUpload" aria-expanded="false" aria-controls="collapseUpload">Upload ảnh</button>			
					</div>

					<div class="collapse" id="collapseSearch" style="margin-top: 10px">
						<input type="text" placeholder="Nhập từ khóa tìm kiếm thay thế" class="enter-input-search form-control">
						<hr>
						<button type="button" class="btn btn-primary" onclick="searchImage()">Tìm  kiếm</button>
					</div>

					<div class="collapse" id="collapseUpload" style="margin-top: 10px">
						<form name="upload-image" class="upload-image" method="post" enctype="multipart/form-data">
							<input type="file" accept="image/*" name="file" required>
							<hr>
							<input type="hidden" name="id" class="id-word">
							<button type="submit" class="btn btn-primary">Upload</button>
						</form>
					</div>

					<div class="result">

					</div>

					<button type="button" class="btn btn-default btn-load-more">Thêm ảnh</button>
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

	<button type="button" class="btn btn-refresh btn-success" onclick="location.reload()">Làm mới</button>

	<script>
		var listImage = [];
		var indexPage = 0;
		var idWord;

		var soundAudio = function (id) {
			var urlSound = $('.btn-sound-' + id).attr('urlSound');
			var audio = new Audio(urlSound);			
			audio.play();
		}

		var showImage = function (id) {
			var word = $('.btn-show-image-' + id).attr('word');
			var mean = $('.btn-show-image-' + id).attr('mean');			
			indexPage = 0;
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

		var searchImage = function () {
			listImage = [];
			indexPage = 0;
			var newWord = $('#modal-show-image .enter-input-search').val();
			if (newWord == null || newWord == '') {
				alert('Điền từ cần tìm kiếm');
				return;
			}

			$('#modal-show-image .modal-title').html('Ảnh cho từ ' + newWord);

			$('.cover').css('display', 'block');
			$.ajax({
				url: '<?php echo Asset("lay-danh-sach-anh") ?>',
				type: 'post',
				data: {id : idWord, newWord : newWord},
				success: function (data) {
					$('.cover').css('display', 'none');
					var listUrl = data.url;
					if (listUrl == null) {					
						alert('Có lỗi hệ thống xảy ra');
					} else {
						var str = '';
						for (var i = 0; i < listUrl.length; i++) {						
							str += '<div class="col-md-3"><div class="box"><img src="'+ listUrl[i].url +'" height="300px" width="100%"><button onclick="downloadImage('+ data.id +', '+ i +')" class="btn btn-primary">Chọn</button></div></div>';
							listImage.push({
								index: i,
								url : listUrl[i].url
							})
						}					
						$('#modal-show-image .modal-body .result').html(str);
					}					
				},
				error: function () {
					$('.cover').css('display', 'none');
					alert('Có lỗi hệ thống xảy ra');
				}
			})
		}

		var excutedImage = function (id) {
			$('.btn-' + id).css('pointer-events', 'none');
			$.ajax({
				url : '<?php echo Asset("hoan-thanh-duyet-anh") ?>',
				type : 'post',
				data : {id : id},
				success : function (data) {
					if (data.status == 200) {
						$('.btn-' + id).removeClass('btn-primary');
						$('.btn-' + id).addClass('btn-success');						
					} else {
						alert('Có sự cố xảy ra');	
					}
				},
				error : function () {
					alert('Có sự cố xảy ra');
				}
			})
		}

		var fixMean = function (id) {
			var word = $('.btn-fix-word-' + id).attr('word');
			var mean = $('.btn-fix-word-' + id).attr('mean');
			var phonectic = $('.btn-fix-word-' + id).attr('phonetic');
			var des = $('.btn-fix-word-' + id).attr('des');
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

		$(".upload-image").on('submit',(function(e) {
			$('.cover').css('display', 'block');
			$('#modal-show-image .loadding').css('display', 'block');
			e.preventDefault();
			$('.upload-image .id-word').val(idWord);
			$.ajax({
				url: '<?php echo Asset("upload-anh") ?>',
				type: "POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {		
					$('.cover').css('display', 'none');			
					if (data.status == 0) {
						location.reload();
					} else {
						alert('Có lỗi trong quá trình xử lý');
						$('#modal-show-image .loadding').css('display', 'none');
						$('#modal-show-image').modal('hide');						
					}
				}
			});
		}));

		$('.btn-search-more').click(function () {
			$('#collapseUpload').collapse("hide")
		})

		$('.btn-upload-more').click(function () {
			$('#collapseSearch').collapse("hide")
		})
	</script>

@endsection