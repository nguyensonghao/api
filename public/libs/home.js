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

	var baseUrlApi = "http://api.mazii.net/";
    var urlAddNote = baseUrlApi + 'api/get-new';
    var dataSend = {skip : 0, take: 20}

    $http.post(urlAddNote, dataSend)
    .success(function (data) {           
        console.log(data);
    })
}]);