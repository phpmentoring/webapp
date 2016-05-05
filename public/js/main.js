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

            if (typeof tags === 'string') {
                tags = tags.toLowerCase();
                tags = tags.split(",");
            }
            
            var lTags = tags.length;
            for(var i in mentor.mentorTags) {
                var name = mentor.mentorTags[i].name.toLowerCase();
                for(var x = 0; x < lTags; x++) {
                    var tag = tags[x].trim();

                    if(name.indexOf(tag) > -1 && tag != "") {
                        return true;
                    }
                }
            }

            return false;
        })
    }
});

mentoringApp.filter('unsafe', function ($sce) {
    return $sce.trustAsHtml;
});

mentoringApp.directive('markdownPreview', function ($http, $sce, $timeout) {
    return {
        replace: true,
        link: function ($scope, element, attrs) {
            $scope.generatePreview = function () {
                $scope.loading = true;
                var body = $('#' + attrs.rawBody).val();
                $http.post('/api/v0/to-markdown', {raw: body}).success(function (data) {
                    $scope.loading = false;
                    if (!("markdown" in data)) {
                        $scope.loadingError = true;
                    } else {
                        $scope.preview = $sce.trustAsHtml(data.markdown);
                        $timeout(function () {
                            Prism.highlightAll();
                        }, 50);
                    }
                }).error(function (data, status, headers, config) {
                    $scope.loading = false;
                    $scope.loadingError = true;
                });
            };
            $('#' + attrs.generateClick).click(function () {
                $scope.generatePreview();
            });
        },
        template: '<div calss="markdown_preview"><div data-ng-show="!loading"> <span class="markdown_preview" ng-bind-html="preview" data-ng-show="!loadingError"></span> <div class="alert alert-danger" role="alert" data-ng-show="loadingError"> <strong>Oops!</strong> There was an error while trying to generate your preview. </div> </div> <div data-ng-show="loading"> <p>Loading...</p> </div></div>'
    };
});

controllers.MentorSearchController = function($scope, $http, $timeout) {
    $scope.mentors = [];
    $scope.loadingError = false;

    $http.get('/api/v0/mentors').
        success(function(data, status, headers, config){
            $scope.mentors = data;
            $scope.random = function() {
                return 0.5 - Math.random();
            },
            $timeout(function () {
                Prism.highlightAll();
            }, 50);
        }).
        error(function(data, status, headers, config) {
            $scope.loadingError = true;
        });
};

controllers.ApprenticeSearchController = function($scope, $http, $timeout) {
    $scope.apprentices = [];
    $scope.loadingError = false;

    $http.get('/api/v0/apprentices').
        success(function(data, status, headers, config){
            $scope.apprentices = data;
            $timeout(function () {
                Prism.highlightAll();
            }, 50);
        }).
        error(function(data, status, headers, config) {
            $scope.loadingError = true;
        });
};

mentoringApp.controller(controllers);
