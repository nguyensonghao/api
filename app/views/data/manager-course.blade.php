@extends('data.layout')

@section('content')

<div class="col-md-12 manager-course-content">
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

	<ul class="list-group">
		<li class="list-group-item title bg-primary">DANH SÁCH CÁC KHÓA HỌC</li>

		@foreach($listCourse as $key => $value)
			<li class="list-group-item">
				<p class="name">{{ $value->name }} => {{ $value->id }}</p>
				<div class="information">
					<p>Lượng subject: {{ $value->subject }}</p>
					<p>Lượng word: {{ $value->word }}</p>
				</div>
				<div class="status">
					@if (is_null($value->status) || $value->status == 0)
						<span>Trạng thái: Chưa phát hành</span><br>
						<a href="{{ Asset('phat-hanh-khoa-hoc') . '/' . $value->id .'/1'}}" 
						class="btn btn-success">Phát hành</a>
					@else
						<span class="public">Trạng thái: Phát hành</span><br>
						<a href="{{ Asset('phat-hanh-khoa-hoc') . '/' . $value->id .'/0'}}" 
						class="btn btn-success">Hủy phát hành</a>
					@endif
				</div>				
			</li>
		@endforeach
	</ul>

	{{ $listCourse->links() }}
</div>

@endsection

<style type="text/css">
	.manager-course-content .title {
		background: #337ab7;
	    border-color: #337ab7;
	    border-radius: 0px !important;
	    margin-top: 20px;
	    padding: 16px;
	}

	.name {
		font-size: 16px;
	}

	.information {
		font-size: 13px;
	    font-style: italic;
	    color: gray;
	}

	.status {
		color: #F44336;
	}

	.status .public {
		color: #337ab7;
	}

	.status .btn {
		border-radius: 0px !important;
		margin-top: 10px;
	}

	.alert {
		border-radius: 0px !important;
		margin-top: 10px;
	}
</style>