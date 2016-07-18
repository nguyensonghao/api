$(document).ready(function () {
	$('.enter-submit').click(function () {
		var keySearch = $('.form-search .search-enter').val();
		if (keySearch == null || keySearch == '') {
			alert('Điền thông tin trước khi tìm kiếm');
		} else {
			$('.form-search').submit();
		}
	})

	$('.search-enter').keypress(function (event) {
		if ( event.which == 13 ) {
			var keySearch = $('.form-search .search-enter').val();
			if (keySearch == null || keySearch == '') {
				alert('Điền thông tin trước khi tìm kiếm');
			} else {
				$('.form-search').submit();
			}	    
		}
	})
})