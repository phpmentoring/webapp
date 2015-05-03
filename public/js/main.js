var mentoringApp = angular.module('mentoringApp', []);
var controllers = {};

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

controllers.MenteeSearchController = function($scope, $http) {
    $scope.mentees = [];
    $scope.loadingError = false;

    $http.get('/api/v0/mentees').
        success(function(data, status, headers, config){
            $scope.mentees = data;
        }).
        error(function(data, status, headers, config) {
            $scope.loadingError = true;
        });

    console.log($scope.mentors);
};

mentoringApp.controller(controllers);