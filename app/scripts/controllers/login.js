'use strict';

/**
 * @ngdoc function
 * @name thecorrespondentApp.controller:AboutCtrl
 * @description
 * # AboutCtrl
 * Controller of the thecorrespondentApp
 */
angular.module('thecorrespondentApp')
  .controller('LoginCtrl', ['$scope','serverFactory','$location', function($scope,serverFactory,$location) {
    serverFactory.getitem(-1,'appuser',$scope,'gotblankappuserobject');
    $scope.gotblankappuserobject = function(data){
      $scope.appuser = data;
    };
    $scope.loginuser = function (){
      serverFactory.checkusercredentials($scope,'checkedusercredentials');
    };

    $scope.checkedusercredentials = function (data){
      if (data.validuser){
        $scope.invalidusermsg = '';
        $location.path( "/" + data.redirect);
      }
      else {
        $scope.invalidusermsg = data.invalidusermsg;
      }
    };


  }]);
