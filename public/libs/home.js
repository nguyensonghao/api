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

    var dataSend = {
        userId : 38,
        timeLocal : '2015-04-21T04:06:38.790Z',
    }

    $http.post('api/pull-note', dataSend)
    .success(function (data) {
        console.log(data);
    })
    
	
}]);