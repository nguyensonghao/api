
angular.module('app', ['ui.router'])

.config(["$stateProvider", "$urlRouterProvider", "$sceProvider", function($stateProvider, $urlRouterProvider, $sceProvider) {
    
    $urlRouterProvider.otherwise('home');
    
    $stateProvider
    
    .state('login', {
        url: '/login',
        views: {
            "main": {
                templateUrl: 'public/libs/login.html',
                controller: 'LoginController'
            }
        }
    })

    .state('register', {
        url: '/register',
        views: {
            "main": {
                templateUrl: 'public/libs/register.html',
                controller: 'RegisterController'
            }
        }
    })

    .state('home', {
        url: '/home',
        views: {
            "main": {
                templateUrl: 'public/libs/home.html',
                controller: 'HomeController'
            }
        }
    })
}])
.run(['$rootScope', 'localstoreServ', '$http', '$state', function ($rootScope, localstoreServ, $http, $state) {
    // var randomString = function (random) {
    //     var listChar = new Array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '~', '?', '[', ']');
    //     var result = '';
    //     for (var i = 0; i < random; i++) {
    //         result += listChar[Math.floor(Math.random() * listChar.length)];
    //     }
    //     return result;
    // }

    var encodeToken = function (tokenId) {
        return tokenId;
    }

    var tokenId = localstoreServ.getItem('token');
    if (tokenId == null) {
        $rootScope.user = null;
    } else {
        var time = Date.now().toString();
        var randomString = localstoreServ.randomString(50);
        var dataSend = localstoreServ.encodeToken(tokenId + time, randomString);
        $http.post('api/init-login', dataSend)
        .success(function (data) {
            console.log(data);
            $rootScope.user = data;
            $rootScope.$broadcast('init');
        });
    }

    $rootScope.logout = function () {
        if ($rootScope.user.email == null)
            return;

        var dataSend = {
            email : $rootScope.user.email
        }
        $http.post('api/logout', dataSend)
        .success(function (data) {
            console.log(data);
            if (data.status == 200) {
                localstoreServ.deleteItem('token');
                $rootScope.user = null;
                $state.go('home');
            } else {
                alert('Đăng xuất lỗi');
            }
        })

    }

    

}])