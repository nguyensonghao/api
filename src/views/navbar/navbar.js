angular.module('App')
.controller('NavbarController', ['$scope', function ($scope){
	
	var showMenuLeft = false;


	var showMenu = function () {
		if (showMenuLeft) {
			$('.cover').css('display', 'none');			
			$('.menu-left').css('width', 0);
		} else {
			$('.cover').css('display', 'block');
			$('.menu-left').css('width', 275);
		}
		showMenuLeft = !showMenuLeft;
	}

	$scope.showMenu = function () {		
		showMenu();
	}

	$('.cover').click(function () {
		showMenu();	
	})

}])