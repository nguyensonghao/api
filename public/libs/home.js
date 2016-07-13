angular.module('app').controller('HomeController', 
	['$scope', '$http', '$rootScope', function ($scope, $http, $rootScope) {

	$scope.$on('init', function () {
		if ($rootScope.user == null) 
			$scope.logined = false;
		else 
			$scope.logined = true;
	})
	
	if ($rootScope.user == null) 
		$scope.logined = false;
	else 
		$scope.logined = true;

	var urlInfo = 'http://api.mazii.net/api/pull-data';
    var dataSend = {
        userId : 33,
        skip : 1
    }

    $http.post(urlInfo, dataSend)
    .success(function (data) {
        console.log(data);
    })
}]);
