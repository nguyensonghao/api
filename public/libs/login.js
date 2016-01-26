angular.module('app').controller('LoginController', 
	['$scope', '$http', '$rootScope', '$state', 'localstoreServ', '$q', function ($scope, $http, $rootScope, $state, localstoreServ, $q) {

	$scope.resultLogin = false;

	$scope.login = function (email, password) {
		var dataSend = {
			email    : email,
			password : password
		}

		$http.post('api/login', dataSend)
		.success(function (data) {
			console.log(data);
			if (!data.status) 
				$scope.resultLogin = true;
			else {
				$scope.resultLogin = false;
				saveToken(data.tokenId);
				alert('Đăng nhập thành công');
				$rootScope.user = data;
				$state.go('home');
			}
			
		})
	}

	$scope.addMean = function (mean) {
		var dataSend = {
			email    : 'nguyensonghao974@gmail.com',
			wordId : '4343434343',
			mean : mean
		}

		$http.post('api/add-mean', dataSend)
		.success(function (data) {
			console.log(data);			
		})
	}

	var saveToken = function (token) {
		localstoreServ.setItem('token', token);
	}

	$scope.click = function (email) {
        var urlResetPassword = 'api/reset-password';
        var dataSend = {
            email : email
        }

        $http.post(urlResetPassword, dataSend)
        .success(function (data) {
        	console.log(data);
        })
	}

	$scope.changeUsername = function (username) {
		var deferred = $q.defer();
        var urlChangeUsername = 'api/change-username';
        var dataSend = {
            email    : 'nguyensonghao974@gmail.com',
            username : username
        }

        console.log(dataSend);

        $http.post(urlChangeUsername, dataSend)
        .success(function (data) {
            console.log(data);
        })
        return deferred.promise;
	}
	
}]);