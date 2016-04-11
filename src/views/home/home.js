angular.module('App')
.controller('HomeController', ['$scope', '$rootScope', function($scope, $rootScope) {

	$scope.loadDone = false;

	setTimeout(function () {
		$scope.loadDone = true;
	}, 100)
}])