angular.module('App')
.controller('LearnController', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http) {

	$scope.loadDone = false;

	setTimeout(function () {
		$scope.loadDone = true;
	}, 100)
}])