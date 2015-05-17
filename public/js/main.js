var mentoringApp = angular.module('mentoringApp', []);
var controllers = {};

mentoringApp.filter('apprenticeTags', function() {
    return function(apprentices, tags) {
        return apprentices.filter(function(apprentice) {
            if(typeof tags === 'undefined' || tags.length == 0) {
                return true;
            }

            tags = tags.toLowerCase();
            for(var i in apprentice.apprenticeTags) {
                var name = apprentice.apprenticeTags[i].name.toLowerCase();
                if(name.indexOf(tags) > -1) {
                    return true;
                }
            }

            return false;
        })
    }
});

mentoringApp.filter('mentorTags', function() {
    return function(mentors, tags) {
        return mentors.filter(function(mentor) {
            if(typeof tags === 'undefined' || tags.length == 0) {
                return true;
            }

            tags = tags.toLowerCase();
            for(var i in mentor.mentorTags) {
                var name = mentor.mentorTags[i].name.toLowerCase();
                if(name.indexOf(tags) > -1) {
                    return true;
                }
            }

            return false;
        })
    }
});

controllers.MentorSearchController = function($scope, $http) {
    $scope.mentors = [];
    $scope.loadingError = false;

    $http.get('/api/v0/mentors').
        success(function(data, status, headers, config){
            $scope.mentors = data;
        }).
        error(function(data, status, headers, config) {
            $scope.loadingError = true;
        });

    console.log($scope.mentors);
};

controllers.ApprenticeSearchController = function($scope, $http) {
    $scope.apprentices = [];
    $scope.loadingError = false;

    $http.get('/api/v0/apprentices').
        success(function(data, status, headers, config){
            $scope.apprentices = data;
        }).
        error(function(data, status, headers, config) {
            $scope.loadingError = true;
        });

    console.log($scope.mentors);
};

mentoringApp.controller(controllers);