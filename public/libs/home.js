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
        noteName : 'gfg',
        noteMean : 'gfgf',
        categoryId : 1
    }

    $http.post('api/add-note', dataSend)
    .success(function (data) {
        console.log(data);
    })
    
	
}]);