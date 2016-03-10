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
  .controller('UserManagerCtrl', ['$scope','serverFactory','$location', function($scope,serverFactory,$location) {
    $scope.roleHelpText = "When creating users, you will need to define a role for a user. This role definition will decide what the user can and cannot do in the CMS. Choose a role in the drop-down to read a short description.";
    var userObject = serverFactory.getUserObject();
    if (userObject === null){
      alert('You got to this page without a valid login. You will now be redirected to the log in page.')
      $location.path( "/");
    }
    else {
      $scope.userrole = userObject.role;
    }
    // debug code - next line
    // $scope.userrole = 'a';

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
    $scope.showdesc = function(){
      switch ($scope.appuserdata.role) {
        case 'a':
        $scope.roleHelpText = "User with <strong>ALL</strong> permissions. This user will be able to perform every task this is available in the CMS. <p class='text-danger'>Please be careful while giving Administrator access to any user.</p>";
        break;
        case 'e':
        $scope.roleHelpText = "User with editors permissions. This user will be able performs all editorial tasks.<p class='text-info'>The user will not, for example, be given access to the User manager console.</p>";
        break;
        case 'w':
        $scope.roleHelpText = "User with correspondent permissions. This user will be able performs story editorial tasks. The user will be able to create and publish stories. However, the user will NOT have access to create or view aggregate pages.";
        break;
        case 'n':
         $scope.roleHelpText = "User with <strong>NO</strong> permissions. For example, use this to remove all permissions for a user but retain the user in your system.";
        break;
      }
    };

  }]);
