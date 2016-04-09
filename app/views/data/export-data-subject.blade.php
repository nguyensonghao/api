@extends('data.layout')

@section('content')

	<div class="export">
		@if (Session::has('error'))
			<p></p>
			<div class="alert alert-danger">				
				<strong>Lỗi!</strong> {{ Session::get('error') }}
			</div>
		@endif

		@if (Session::has('notify'))
			<p></p>
			<div class="alert alert-success">
				<strong>Thông báo!</strong> {{ Session::get('notify') }}
			</div>
		@endif

		<ul class="list-group">
			<li class="title bg-primary">
				<span class="glyphicon glyphicon-list-alt"></span> 
				Danh sách các khóa học
			</li>
			@foreach ($listCourse as $key => $value)
				<li class="list-group-item">
					<span class="badge">{{ $value['word'] }} từ</span>
					<span class="badge">{{ $value['subject'] }} khóa con</span>
					<p>Khóa học: {{ $value['name'] }}({{ $value['type'] }})</p>
					<a href="{{ Asset('sap-xep-du-lieu') . '/' . $value['id'] }}" class="btn btn-success btn-sm">
						Xuất dữ liệu
					</a>
				</li>
			@endforeach
		</ul>
	</div>

@endsection