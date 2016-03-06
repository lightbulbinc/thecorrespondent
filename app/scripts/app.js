'use strict';

/**
 * @ngdoc overview
 * @name thecorrespondentApp
 * @description
 * # thecorrespondentApp
 *
 * Main module of the application.
 */
angular
  .module('thecorrespondentApp', [
    // 'ngAnimate',
    // 'ngCookies',
    // 'ngResource',
    'ngRoute',
    // 'ngSanitize',
    // 'ngTouch',
    'ngCkeditor'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/login.html',
        controller: 'LoginCtrl',
        controllerAs: 'login'
      })
      .when('/pagemanager', {
        templateUrl: 'views/pagemanager.html',
        controller: 'PageManagerCtrl',
        controllerAs: 'pagemanager'
      })
      .when('/queryemanager', {
        templateUrl: 'views/queryemanager.html',
        controller: 'QueryeManagerCtrl',
        controllerAs: 'queryemanager'
      })
      .when('/usermanager', {
        templateUrl: 'views/usermanager.html',
        controller: 'UserManagerCtrl',
        controllerAs: 'usermanager'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
