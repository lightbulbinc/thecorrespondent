'use strict';
// the following two statements allow the use of $ and alter in the script and prevent the grunt errors messages
/*global $:false */
/*global alert:false */

/**
 * @ngdoc function
 * @name thecorrespondentApp.controller:AboutCtrl
 * @description
 * # AboutCtrl
 * Controller of the thecorrespondentApp
 */
angular.module('thecorrespondentApp')
  .controller('UserManagerCtrl', ['$scope','serverFactory', function($scope,serverFactory) {
    // TODO:
    // need to handle the case where a user comes to this page directly.
    // Will use the login session (probably via the serverFactory), to check if this is a valid user, else send user to login page
    // Or probably better is to display a message on the complete page saying something like "You need to log in..."

    serverFactory.getitems('appuser',$scope,'gotappusers');
    $scope.gotappusers = function(data){
      $scope.appusers = data.Items;
      $('#waitmodal').modal('hide');
    };

    $scope.changepassword = {
      newpassword:'',
      confirmnewpassword:'',
    };

    $scope.disablesave = function(){
      if (!angular.isDefined($scope.appuserdata)){
        return true;
      }
      else {
        if ($scope.appuserdata.name === null){
          return true;
        }
        if ($scope.appuserdata.username === null){
          return true;
        }
        if ($scope.appuserdata.role === null){
          return true;
        }
      }
      return false;
    }

    $scope.disabledelete = function(){
      if (!angular.isDefined($scope.appuserdata)){
        return true;
      }
      else {
        if ($scope.appuserdata.id === null){
          return true;
        }
      }
      return false;
    }

    $scope.itemstyle = function(appuserid){
      var retval = 'list-group-item';
      if (angular.isDefined($scope.appuserdata) && $scope.appuserdata !== null){
        if (appuserid === $scope.appuserdata.id){
          retval += ' active';
        }
      }
      return retval;
    };


    $scope.getthisitemdetails = function(appuserid){
      $('#waitmodal').modal('show');
      serverFactory.getitem(appuserid,'appuser',$scope,'gotappuser');
    };
    $scope.gotappuser = function (data){
      $scope.appuserdata = data;
      $scope.changepassword.newpassword = '';
      $scope.changepassword.confirmnewpassword = '';
      $('#waitmodal').modal('hide');
    };


    // new app user
    $scope.new = function() {
      $('#waitmodal').modal('show');
      serverFactory.getitem(-1,'appuser',$scope,'gotappuser');
    };
    // new app user - END

    // save app user
    $scope.save = function() {
      var savethisuser = false;
      if ($scope.appuserdata.password === null){  //means this is a new user, so new password entry is mandatory
        if ($scope.changepassword.newpassword.trim() === ''){
          alert('Please enter a password for the new user. Your password must be at least 5 characters long.');
        }
        else {  //if new password is set then ensure that confirmnewpassword and newpassword are identical
          if ($scope.changepassword.newpassword.trim().length < 5){
            alert('Your password is ' + $scope.changepassword.newpassword.trim().length + ' characters long. Your password must be at least 5 characters long.');
          }
          else {
            if ($scope.changepassword.newpassword.trim() !== $scope.changepassword.confirmnewpassword.trim()){
              alert('Please ensure that the new password and confirm new password are identical.');
            }
            else {  //all is good so put the new password in the appuser password field and submit
              $scope.appuserdata.password = $scope.changepassword.newpassword.trim();
              savethisuser = true;
            }
          }
        }
      }
      // password not null means this is an existing user
      // so change the password only if a user has entered something in new password
      // if user has not entered anything in the new password field, we will not do anything with the password
      else {
        if ($scope.changepassword.newpassword.trim() !== ''){
          if ($scope.changepassword.newpassword.trim().length < 5){
            alert('Your password is ' + $scope.changepassword.newpassword.trim().length + ' characters long. Your password must be at least 5 characters long. If you do not want to change this users password, clear out the contents of tne new passwowrd field');
          }
          else {
            if ($scope.changepassword.newpassword.trim() !== $scope.changepassword.confirmnewpassword.trim()){
              alert('Please ensure that the new password and confirm new password are identical. If you do not want to change this users password, clear out the contents of tne new passwowrd field');
            }
            else {  //all is good so put the new password in the appuser password field and submit
              $scope.appuserdata.password = $scope.changepassword.newpassword.trim();
              savethisuser = true;
            }
          }
        }
        // if the user has not set the new password then simply save
        // TODO: we need to figure out a dirty form functionality here to make sure we only save if user has made some changes
        else {
          savethisuser = true;
        }
      }
      // make sure the user name is not already taken
      if (savethisuser){  //but only make this check if the previous checked worked. else we'll see multiple dialogs
        angular.forEach($scope.appusers,function(appuser){
          if (appuser.username === $scope.appuserdata.username && appuser.id !== $scope.appuserdata.id){
            alert('This user name is already taken. Please enter a unique user name');
            savethisuser = false;
          }
        });
      }
      if (savethisuser){
        $('#waitmodal').modal('show');
        serverFactory.saveitemdetails($scope,$scope.appuserdata,'appuser','savedappuser');

      }
    };

    $scope.savedappuser = function(data) {
      $scope.appuserdata = data;
      serverFactory.getitems('appuser',$scope,'gotappusers');
    };
    // save app user - END

    // delete user
    $scope.delete = function() {
      $('#waitmodal').modal('show');
      serverFactory.deleteitem($scope.appuserdata.id,'appuser',$scope,'deletedappuser');
    };
    $scope.deletedappuser = function() {
      $scope.appuserdata = null;
      serverFactory.getitems('appuser',$scope,'gotappusers');
    };
    // delete user - END

    $scope.formgroupClass = function(){
      return "form-group has-error";
    }

  }]);
