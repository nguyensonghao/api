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
        noteId : 4,
    }

    $http.get('api/delete-note', dataSend)
    .success(function (data) {
        console.log(data);
    })
    
	
}]);