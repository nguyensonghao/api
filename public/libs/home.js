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

	var string = '[{"type":"word","query":"のに","date":1461210621514,"category":4,"isRemember":0,"id":3}]';
	var listNote = JSON.parse(string);
    var listDataSend = [];
    var size = listNote.length;
    var deferred = $q.defer();
    var urlAddNote = baseUrlApi + 'api/push-note-new';
    for (var i = 0; i < size; i++) {
        var note = listNote[i];            
        if (note.type != 'grammar_detail') {
            var idx = '-1';
        } else {
            var idx = note.idx;
        }

        var dataSend = {
            noteName   : note.query,
            noteMean   : '',
            categoryId : note.category,
            type       : note.type,                
            idx        : idx,
            updated_at : new Date(note.date)
        }
        
        listDataSend.push(dataSend);
    }                

    $http.post(urlAddNote, {userId : 38, listNote: JSON.stringify(listDataSend)})
    .success(function (data) {           
        console.log(data);
    })
}]);