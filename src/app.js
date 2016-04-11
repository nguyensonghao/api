var app = angular.module('App', ['ui.router', 'chart.js', 'ngAnimate'])

app.config(["$stateProvider", "$urlRouterProvider", "$sceProvider", function($stateProvider, $urlRouterProvider, $sceProvider) {
    
    $urlRouterProvider.otherwise('home');    
    
    $stateProvider    
   
    .state('home', {
        url: '/home',
        views: {
            "main": {
                templateUrl: 'views/home/main.html',
                controller: 'HomeController'
            }
        }
    })

    .state('description', {
        url: '/description',
        views: {
            "main": {
                templateUrl: 'views/description/main.html',
                controller: 'DescController'
            }
        }
    })

    .state('experience', {
        url: '/experience',
        views: {
            "main": {
                templateUrl: 'views/experience/main.html',
                controller: 'ExpController'
            }
        }
    })

    .state('skill', {
        url: '/skill',
        views: {
            "main": {
                templateUrl: 'views/skill/main.html',
                controller: 'SkillController'
            }
        }
    })

    .state('learning', {
        url: '/learning',
        views: {
            "main": {
                templateUrl: 'views/learning/main.html',
                controller: 'LearnController'
            }
        }
    })

    .state('contact', {
        url: '/contact',
        animation: 'first',
        views: {
            "main": {
                templateUrl: 'views/contact/main.html',
                controller: 'ContactController'
            }
        }
    })

    $sceProvider.enabled(false);
    
}]).run(["$rootScope", function ($rootScope) {
    $rootScope.$on('$stateChangeSuccess', function(ev, to, toParams, from, fromParams) {                
        $rootScope.loadDone = false;
        $rootScope.fromState = from.name;
        $rootScope.toState = to.name;

        if (to.name == 'home') {            
            $('.content .main').addClass('view-home');
        } else {            
            $('.content .main').removeClass('view-home');
        }

        $rootScope.menuActive = function (state) {
            if (state == $rootScope.toState)
                return 'active';
        }
    });    
}])
