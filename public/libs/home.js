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
        userId : 30,
        date   : 'fdfd',
        categoryName : 'fdfdfd'
    }

    $http.post('api/add-category', dataSend)
    .success(function (data) {
        console.log(data);
    })
    
	
}]);