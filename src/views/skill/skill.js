angular.module('App')
.controller('SkillController', ['$scope', '$rootScope', function($scope, $rootScope) {

	$scope.loadDone = false;

	setTimeout(function () {
		$scope.loadDone = true;
	}, 100)

	$('.percent').css('display', 'none');

	$scope.listSkill = [
		{
			name : 'HTML/CSS',
			labels : ["Hoàn thành", "Chưa hoàn thành"],
			data : [80, 20],
			fluent : [1, 2, 3, 4],
			bad : [1],
			options : {percentageInnerCutout : 90, tooltipTemplate: "<%= value %>%"},		    
			desc : "<p>Tương đối thành thạo HTML. Thiết kế giao diện thường, responsive sử dụng bootstrap 3 và ionic cho web app</p>"
		},
		{
			name : 'Javascript',
			labels : ["Hoàn thành", "Chưa hoàn thành"],
			data : [80, 20],
			fluent : [1, 2, 3, 4],
			bad : [1],
			options : {percentageInnerCutout : 90, tooltipTemplate: "<%= value %>%"},		    
			desc: "<p>Có kinh nghiệm xây dựng front-end, web app sử dụng Jquery, Angular 1</p>"
		},
		{
			name : 'PHP',
			labels : ["Hoàn thành", "Chưa hoàn thành"],
			data : [60, 40],
			fluent : [1, 2, 3],
			bad : [1, 2],
			options : {percentageInnerCutout : 90, tooltipTemplate: "<%= value %>%"},
			desc: "<p>Xây dựng trang web độc lập, thiết kết api cho các ứng dụng đa nền tảng sử dụng Framework Laravel 4.2</p>"
		},
		{
			name : 'Node.js',
			labels : ["Hoàn thành", "Chưa hoàn thành"],
			data : [40, 60],
			fluent : [1, 2],
			bad : [1, 2, 3],
			options : {percentageInnerCutout : 90, tooltipTemplate: "<%= value %>%"},
			desc: "<p>Chưa thực sự tốt, chủ yếu sử dụng để crawler dữ liệu và xây dựng các module real time như chat</p>"
		},
	]	

	var loadTextInside = function () {
		var height = $('.chart-container').height();		
		var width  = $('.chart-container').width();
		$('.percent').css('left', width / 2 + 'px');
		$('.percent').css('top', height / 2 - 10 + 'px');
		$('.percent').css('display', 'inline');		
	}

	var increaseNumberEffect = function () {
		$('.count').each(function () {
		    $(this).prop('Counter', 0).animate({
		        Counter: $(this).text()
		    }, {
		        duration: 3000,
		        easing: 'swing',
		        step: function (now) {
		            $(this).text(Math.ceil(now));
		        }
		    });
		});
	}

	setTimeout(function () {
		loadTextInside();
	}, 1000);

	setTimeout(function () {
		increaseNumberEffect();
	}, 1000);

}])