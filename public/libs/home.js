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

	var baseUrlApi = "http://api.mazii.net/api/kanji/にほん/10";

    $http.get(baseUrlApi)
    .success(function (data) {           
        console.log(data);
    })
}]);
