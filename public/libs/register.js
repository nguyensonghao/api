angular.module('app').controller('RegisterController', 
['$scope', '$http', '$state', function ($scope, $http, $state) {

	$scope.register = function (email, password) {
		var dataSend = {
			email : email,
			password : password
		}


		$http.post('api/register', dataSend)
		.success(function (data) {
			if (data.status == 304) {
				alert('Tài khoản đã tồn tại');
			} else if (data.status == 302) {
				alert('Có lỗi xảy ra trong quá trình');
			} else {
				alert('Đăng ký tài khoản thành công, tới trang đăng nhập');
				$state.go('login');
			}
		})	
	}
	
}]);