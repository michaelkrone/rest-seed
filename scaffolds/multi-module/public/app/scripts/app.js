'use strict';

angular.module('##APP_ANGULAR_APP_NAME##', [])
  .config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {

    $locationProvider.html5Mode(true);

    $routeProvider
      .when('/', {
        templateUrl: '##APP_VIEW_MODULE##/home',
        controller: 'MainCtrl'
      })

      .otherwise({
        redirectTo: '/'
      });
  }]);
