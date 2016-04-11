angular.module('App')
.controller('ExpController', ['$scope', '$rootScope', function($scope, $rootScope) {

	$scope.loadDone = false;

	setTimeout(function () {
		$scope.loadDone = true;
	}, 100)

	$scope.showSchoolTab = false;

	$scope.changeTab = function (tabName) {
		$('.nav-tabs li a').removeClass('menu-active');
		if (tabName == 'school') {
			$scope.showSchoolTab = true;
			$('.menu-school').addClass('menu-active');
		} else {
			$scope.showSchoolTab = false;
			$('.menu-reality').addClass('menu-active');
		}
	}
}])