'use strict';

/**
 * @ngdoc service
 * @name peterbdotin.util
 * @description
 * # util
 * Factory in the peterbdotin.
 */
angular.module('thecorrespondentApp')
  .factory('util', function () {
    return {
      isProduction: function(){
        return true;
      },
      restAPIURL: function($location) {
        return $location.host() === 'localhost' ? 'http://localhost:9001/services/' : 'services/';
      },
      count: function (arrOrObj) {
        return Array.isArray(arrOrObj) ? arrOrObj.length : Object.keys(arrOrObj).length;
      },
      isEmptyString: function(str) {
        return angular.isUndefined(str) || str ==='' || str === null;
      },
      isVallidEmail: function(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
      },
      getUniqueMenuID: function (pagemenu) {
        var retval = 0;
        angular.forEach (pagemenu,function (item){
          retval = item.id > retval ? item.id : retval;
          angular.forEach (item.items, function (subitem){
            retval = subitem.id > retval ? subitem.id : retval;
          });
        });

        return retval + 1;
      },

      httpPost: function(paramsObject,http,url) {
        var httpPostParams = [];
        for (var key in paramsObject) {
          httpPostParams.push(key + '=' + encodeURIComponent(paramsObject[key]));
        }
        httpPostParams = httpPostParams.join('&');
        http({
          method: 'POST',
          url: url,
          data: httpPostParams,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).
        success(function(data) {
          console.log(data);
        }).
        error(function(data) {
          console.log(data);
        });
      },
    };
  });
