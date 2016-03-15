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
        userId : 1,
        wordId : '1232',
        type : 'kanji'
    }

    $http.post('api/remember-flash', dataSend)
    .success(function (data) {
        console.log(data);
    })
    
	
}]);