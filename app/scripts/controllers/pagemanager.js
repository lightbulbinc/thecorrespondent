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
  .controller('PageManagerCtrl', ['$scope','serverFactory','$location',  function($scope,serverFactory,$location) {
    var userObject = serverFactory.getUserObject();
    if (userObject === null){
      // comment out the following two lines in debug mode
      // alert('You got to this page without a valid login. You will now be redirected to the log in page.')
      // $location.path( "/");

      //debug code
      //comment out the following lines to $scope.appalerts = []; in production

      $scope.userrole ='a';
      $('#waitmodal').modal('show');
      // this variable is used to identify when the page is completely loaded
      $scope.pagelistupdating = true;
      $scope.pagetypefilter = '';
      $scope.pageloadingcounter = 0;
      $scope.pageloadingcounter += 1;
      serverFactory.getpageitems($scope,'gotinitpageitems');
      $scope.pageloadingcounter += 1;
      serverFactory.getitems('pagetemplate',$scope,'gotpagetemplates');
      $scope.pageloadingcounter += 1;
      serverFactory.getitem(1,'appmetadata',$scope,'gotappmetadata');
      $scope.pageloadingcounter += 1;
      serverFactory.getmenu($scope,'gotmenuinit');
      $scope.pidetailsurl = 'picontent';
      $scope.listview = 'pageview';
      $scope.appalerts = [];


    }
    else {
      $scope.userrole = userObject.role;
      $('#waitmodal').modal('show');
      // this variable is used to identify when the page is completely loaded
      $scope.pagelistupdating = true;
      $scope.pagetypefilter = '';
      $scope.pageloadingcounter = 0;
      $scope.pageloadingcounter += 1;
      serverFactory.getpageitems($scope,'gotinitpageitems');
      $scope.pageloadingcounter += 1;
      serverFactory.getitems('pagetemplate',$scope,'gotpagetemplates');
      $scope.pageloadingcounter += 1;
      serverFactory.getitem(1,'appmetadata',$scope,'gotappmetadata');
      $scope.pageloadingcounter += 1;
      serverFactory.getmenu($scope,'gotmenuinit');
      $scope.pidetailsurl = 'picontent';
      $scope.listview = 'pageview';
      $scope.appalerts = [];
    }
    // debug code - next line
    // $scope.userrole = 'a';


    $scope.dontshowdialongagain = {
      pagepublish:false,
      mediapagepublish:false
    };
    $scope.asset = {
      listrefreshcomplete:true,
      gotlatest:false,
    };

    $scope.pageitemcollection = [];

    $scope.showservererrmsg = function (errmsg){
      alert(errmsg);
      $('#waitmodal').modal('hide');
    };

    // start up
    $scope.gotinitpageitems = function(data){
      $scope.pageitems = [];
      // ideally this should happen on the server
      angular.forEach (data,function(pageitem){
        if (pageitem.pagetype === 'piaggregate'){
          if ($scope.userrole !== 'w'){
            $scope.pageitems.push(pageitem);
          }
        }
        else {
          $scope.pageitems.push(pageitem);
        }
      });
      $scope.pagelistupdating = false;

      // $('#waitmodal').modal('hide');
      $scope.pageloadingcounter -= 1;
    };
    // displays in the new item list
    // start up
    $scope.gotpagetemplates = function(data){
      $scope.pagetemplates = [];
      // ideally this should happen on the server
      angular.forEach (data.Items,function(pagetemplate){
        if (pagetemplate.pagetype === 'piaggregate'){
          if ($scope.userrole !== 'w'){
            $scope.pagetemplates.push(pagetemplate);
          }
        }
        else {
          $scope.pagetemplates.push(pagetemplate);
        }
      })

      $scope.pageloadingcounter -= 1;
    };
    // site menu
    // start up
    $scope.gotmenuinit = function(data){
      $scope.sitemenu = data;
      $scope.pageloadingcounter -= 1;
    };
    // start up
    $scope.gotappmetadata = function (data){
      $scope.appmetadata = data;
      $scope.pageloadingcounter -= 1;
    };

    $scope.$watch('pageloadingcounter',function(newValue){
      if (newValue === 0){
        $('#waitmodal').modal('hide');
      }
    });

    $scope.pagelistitemstyle = function(pageitemid){
      var retval = 'list-group-item';
      if (angular.isDefined($scope.pageitemdata)){
        if (pageitemid === $scope.pageitemdata.id){
          retval += ' active';
        }

      }
      return retval;
    };

    $scope.menulistitemstyle = function(pageitemid){
      var retval = 'btn btn-xs';
      if (angular.isDefined($scope.pageitemdata)){
        if (pageitemid === $scope.pageitemdata.id){
          retval += ' btn-primary ';
        }
      }
      return retval;
    };
    $scope.disablesave = function (){
      var retval = true;
      // make sure that a page item is available to save
      if (angular.isDefined($scope.pageitemdata)){
        // if a page item is avialable make sure that all the required fields are filled
        if ($scope.pageitemdata.title !== null){
          retval = false;
        }
      }
      return retval;
    };

    $scope.getthisitemdetails = function(pageitemid){
      $('#waitmodal').modal('show');
      serverFactory.getitem(pageitemid,'pageitem',$scope,'gotpageitem');
    };
    $scope.gotpageitem = function (data){
      $scope.pageitemdata = data;
      $scope.pagetitleinit = data.title;
      $scope.pagetemplate = $scope.pageitemdata.pagetemplate[0];
      $scope.pagetemplate.url = 'views/' + $scope.pageitemdata.pagetemplate[0].pagetype + ".html";
      serverFactory.getpageitemdetailsdata($scope,'gotpageitemdetailsdata');
    };
    $scope.gotpageitemdetailsdata = function (data){
      $scope.pageitemdetailsdata = data;
      $('#waitmodal').modal('hide');
    };

    // new page of a selected template
    $scope.newpage = function (pagetemplate){
      $('#waitmodal').modal('show');
      $scope.pagetemplate = pagetemplate;
      $scope.pagetemplate.url = 'views/' + $scope.pagetemplate.pagetype + ".html";
      serverFactory.getitem(-1,'pageitem',$scope,'gotnewpageitem');
    };

    $scope.gotnewpageitem = function (data){
      $scope.pageitemdata = data;
      $scope.pageitemdata.pagetemplate.push($scope.pagetemplate);
      serverFactory.getitem(-1,$scope.pagetemplate.pagetype,$scope,'gotdetailspage');
    };
    $scope.gotdetailspage = function (data){
      $scope.pageitemdetailsdata = data;
      // TODO:
      // this is a HACK to get the empty aggregate layout items
      // need to make this part of the details page get
      if ($scope.pagetemplate.pagetype === 'piaggregate'){
        serverFactory.getemptylayoutcolumns($scope,'gotemptylayoutcolumns',4);
      }
      else if ($scope.pagetemplate.pagetype === 'picontent'){
        serverFactory.getemptylayoutcolumns($scope,'gotemptylayoutcolumns',3);
      }
      else {
        $('#waitmodal').modal('hide');
      }
    };
    // new page of a selected template - END

    // save a page item
    $scope.save = function (){
      $('#waitmodal').modal('show');
      serverFactory.saveitemdetails($scope,$scope.pageitemdetailsdata,$scope.pagetemplate.pagetype,'savedpageitemdetails');
    };
    $scope.savedpageitemdetails = function (data){
      $scope.pageitemdetailsdata = data;
      // since there's a one-to-one mapping between page master and slave
      $scope.pageitemdata[$scope.pagetemplate.pagetype][0] = $scope.pageitemdetailsdata;
      $scope.pageitemdata.pagetype = $scope.pagetemplate.pagetype;
      serverFactory.saveitemdetails($scope,$scope.pageitemdata,'pageitem','savedpageitem');
    };
    $scope.savedpageitem = function (data){
      $scope.pageitemdata = data;
      $('#waitmodal').modal('hide');
      // we are not going to update the page items list (extremely resource intensive), if the title was not changed
      // later we'll change this to update the page items list is a more efficient manner
      if ($scope.pagetitleinit !== data.title){
        $scope.pagetitleinit = data.title;
        $scope.pagelistupdating = true;
        serverFactory.getpageitems($scope,'gotpageitems');
      }
      // we are now going to publish at save
      $scope.publish();

    };
    $scope.gotpageitems = function(data){
      $scope.pageitems = data;
      $scope.pagelistupdating = false;
    };

    // save a page item - END

    // publish a page item
    $scope.cannotpublish = function(){
      var retval = true;
      if ($scope.pageitemdata){
        if ($scope.pageitemdata.picontent.length === 1 || $scope.pageitemdata.piaggregate.length === 1){
          retval = false;
        }
      }
      return retval;
    }
    $scope.publish = function(){
      if ($scope.dontshowdialongagain.pagepublish === false){
        $('#page_publish_modal').modal('show');
      }
      serverFactory.publishitem($scope.pageitemdata.id,$scope,'pagepublished');
    };
    $scope.pagepublished = function(data){
      var appalert = {
        title:data.title,
        link:data.pagename,
        msg:'Page published at ',
      };
      $scope.appalerts.splice(0,0,appalert);
    };
    $scope.publishall = function(){
      if ($scope.dontshowdialongagain.pagepublish === false){
        $('#page_publish_modal').modal('show');
      }
      serverFactory.publishall($scope,'allpagespublished');
    };
    $scope.allpagespublished = function(data){
      var appalert = {
        title:'Home',
        link:'index.php',
        msg:'All Pages published at ',
      };
      $scope.appalerts.splice(0,0,appalert);
    };
    // publish a page item - END

    // aggregate page

    $scope.gotemptylayoutcolumns = function(data) {
      $scope.pageitemdetailsdata.aggregatecolumn = data;
      $('#waitmodal').modal('hide');
    };
    $scope.getlayoutcolumnsize = function() {
      return 'col-xs-' + Math.round(12/$scope.pageitemdetailsdata.aggregatecolumn.length);
    };
    $scope.addaggregateitemtocolumn = function(layoutcolumn,itemtype,template){
      $scope.selectedlayoutcolumn = layoutcolumn;
      $scope.selectedlayoutitemtype = itemtype;
      $scope.selectedlayouttemplate = template;
      $('#waitmodal').modal('show');
      serverFactory.getitem(-1,'aggregateitem',$scope,'gotaggregateitem');
    };

    $scope.gotaggregateitem = function(data){
      $scope.aggregateitem = data;
      //if the selectedlayoutitemtype is tag, we need to get the updated tag list
      if ($scope.selectedlayoutitemtype === 'tag'){
        serverFactory.getitems($scope.selectedlayoutitemtype,$scope,'openavailablelayoutitemsModal');
      }
      // if user asked for stories then we should use the available story list (see gotpageitems())
      else {
        $scope.openavailablelayoutitemsModal(null);
      }
    };

    $scope.openavailablelayoutitemsModal = function(data){
      $scope.availableitemlist = data === null ? $scope.pageitems : data.Items;
      $scope.layoutitem = null;
      $scope.pagetitle = '';
      $scope.selectedaggregateitemtypeid = -1;
      if ($scope.selectedlayoutitemtype==='tag'){
        angular.forEach ($scope.aggregateitemtypes,function(aggregateitemtype){
          if (aggregateitemtype.title === 'Group'){
            $scope.selectedaggregateitemtypeid = aggregateitemtype.id;
          }
        });
      }
      $scope.aggregateitem.maxlength = -1;
      $('#waitmodal').modal('hide');
      $('#availablelayout' + $scope.selectedlayoutitemtype + 'Modal').modal('show');
    };

    $scope.islayouttagselected = function(){
      return $scope.layoutitem === null;
    };
    // $scope.showmaxlengthfield = function(){
    //   var retval = false;
    //   angular.forEach ($scope.aggregateitemtypes,function(aggregateitemtype){
    //     if (aggregateitemtype.id === $scope.selectedaggregateitemtypeid && aggregateitemtype.lengthismandatory === "1"){
    //       retval = true;
    //     }
    //   });
    //   return retval;
    // };
    $scope.resetselectedtag = function(){
      if ($scope.selectedlayoutitemtype!=='tag'){
        $scope.selectedaggregateitemtypeid = -1;
      }
      $scope.aggregateitem.maxlength = -1;
    };

    $scope.additemtolayoutcolumn = function(){
      var goahead = true;
      angular.forEach ($scope.aggregateitemtypes,function(aggregateitemtype){
        if (aggregateitemtype.id === $scope.selectedaggregateitemtypeid){
          // the following check could have been put into the islayouttagselected function above
          // but that would probably make it a little confusing to users as to why the ok button in the List of Tags dialog is still disabled
          // specifically since the mandatory check is not that obvious
          // so instead we'll alert the user
          if (aggregateitemtype.lengthismandatory === "1" && (isNaN($scope.aggregateitem.maxlength) || $scope.aggregateitem.maxlength < 1)){
            alert("The " + aggregateitemtype.title + " type requires you to enter a maximum length.\nPlease enter a number greater than 0 in the field below.");
            goahead = false;
          }
          else {
            $scope.aggregateitem.aggregateitemtype.push(aggregateitemtype);
          }
        }
      });
      if (goahead){
        $scope.aggregateitem[$scope.selectedlayoutitemtype].push($scope.layoutitem);
        $scope.selectedlayoutcolumn.aggregateitem.push($scope.aggregateitem);
        $('#availablelayout'+ $scope.selectedlayoutitemtype + 'Modal').modal('hide');
      }
    };

    $scope.removeaggregateitemfromcolumn = function(aggregateitemindex,layoutcolumn){
      layoutcolumn.aggregateitem.splice(aggregateitemindex,1);
    };



    // aggregate page - END

    // story tags
    $scope.opentagsdialog = function() {
      $('#storyTagsModal').modal('show');
    };

    $scope.openaddnewtagdialog = function() {
      serverFactory.getitem(-1,'tag',$scope,'gotnewtag');

    };
    $scope.gotnewtag = function (data){
      $scope.newtag = data;
      $('#createTagsModal').modal('show');
    };
    $scope.savetag = function () {
      serverFactory.saveitemdetails($scope,$scope.newtag,'tag','savedtag');
    };
    $scope.savedtag = function(data){
      if (data.ServerErr.indexOf('Duplicate entry') === 0){
        alert('A tag with this name already exists. Change the name of the tag or click Cancel and then click Open to view the list of available tags.');
      }
      else {
        $('#createTagsModal').modal('hide');
      }
    };

    $scope.getavailabletaglist = function() {
      serverFactory.getitems('tag',$scope,'openavailableTagsdialog');
    };
    $scope.openavailableTagsdialog = function(data) {
      $scope.availabletaglist = data.Items;
      $('#availableTagsModal').modal('show');
    };
    $scope.assigntagtostory = function(tagid){
      angular.forEach ($scope.availabletaglist,function(availabletag){
        if (availabletag.id === tagid){
          $scope.pageitemdata.tag.push(availabletag);
        }
      });
    };
    $scope.unassigntagtostory = function(tagid) {
      var selectedIndex = -1;
      for (var i=0;i<$scope.pageitemdata.tag.length;i++){
        var storytag = $scope.pageitemdata.tag[i];
        if (tagid === storytag.id){
          selectedIndex = i;
          break;
        }
      }
      if (selectedIndex > -1){
        $scope.pageitemdata.tag.splice(selectedIndex,1);
      }
    };

    // story tags - END

    // asset handling

    $scope.openassetBrowserDialog = function(chooseTitleImage){
      // just to make sure, set the variable to false
      $scope.chooseTitleImage = false;
      if (angular.isDefined(chooseTitleImage)){
        $scope.chooseTitleImage = true;
      }
      $('#waitmodal').modal('show');
      if ($scope.asset.gotlatest){
        $scope.gotassets();
      }
      else {
        serverFactory.getitemsorderbyattr('asset','id','desc',$scope,'gotassets');
      }
    };

    $scope.gotassets = function(data){
      $scope.listrefreshcomplete = true;
      if (data){
        $scope.pageassets = data.Items;
        $scope.asset.gotlatest = true;
      }
      $('#waitmodal').modal('hide');
      $('#fileBrowserModal').modal('show');
    };

    $scope.uploadfile = function(){
      var file_data = $('#pageasset').prop('files')[0];
      if (angular.isDefined(file_data)){
        serverFactory.fileupload($scope,file_data,'fileuploaddone');
      }
      else {
        $scope.asset.listrefreshcomplete = true;
        alert('Please select a file to upload');
      }
    };

    $scope.fileuploaddone = function(){
      $scope.asset.listrefreshcomplete = false;
      alert('File upload successful');
      $('#pageasset').val('');
      serverFactory.getitemsorderbyattr('asset','id','desc',$scope,'refreshassetlist');
    };

    $scope.refreshassetlist = function(data){
      $scope.pageassets = data.Items;
      $scope.asset.listrefreshcomplete = true;
      // $('#waitmodal').modal('hide');
    };

    $scope.selectimage = function(pageasset){
      // if user chose to add a title image
      if ($scope.chooseTitleImage){
        if ($scope.pageitemdata.pagetemplate[0].pagetype === 'pimediagroup'){
          $scope.pageitemdetailsdata.asset.push(pageasset);
        }
        else {
          $scope.pageitemdetailsdata.titleimage = pageasset.name;
        }
        $('#fileBrowserModal').modal('hide');
      }
      else {
        window.prompt("Press Ctrl+C on your keyboard to copy the URL of the selected asset. Then close this dialog and use Ctrl+V to paste the URL where required.", $scope.appmetadata.clientassetpath + pageasset.name);
        $('#fileBrowserModal').modal('hide');
      }
      // alert(imageurl);
    };
    // asset handling

    // site menu
    $scope.addmenu = function(selectedmenu){
      $('#waitmodal').modal('show');
      $scope.selectedmenu = selectedmenu;
      serverFactory.getitem(-1,'menu',$scope,'gotmenutitle');
    };

    $scope.gotmenutitle = function(data){
      $scope.newmenu = data;
      $scope.insertposition = {
        position:angular.isDefined($scope.selectedmenu) ? parseInt($scope.selectedmenu.position) : 0,
        relation:'after',
      };
      $('#waitmodal').modal('hide');
      $('#menutitledialog').modal('show');
    };

    $scope.savemenutitle = function(){
      serverFactory.insertafter($scope,$scope.newmenu,$scope.insertposition.position,'menu','savedmenu');
    };
    $scope.savedmenu = function(){
      serverFactory.getmenu($scope,'gotmenu');
      $('#menutitledialog').modal('hide');
    };

    $scope.removemenu = function(selectedmenu){
      $('#waitmodal').modal('show');
      serverFactory.deleteitem(selectedmenu.id,'menu',$scope,'removedmenu');
    };
    $scope.removedmenu = function(){
      serverFactory.getmenu($scope,'gotmenu');
    };
    $scope.publishmenu = function() {
      $('#menu_publish_modal').modal('show');
      serverFactory.publishmenu($scope,'menupublished');
    };
    $scope.menupublished = function(data){
      var appalert = {
        title:'Home',
        link:'../index.php',
        msg:'Menu published. See the home page with the updated menu at ',
      };
      $scope.appalerts.splice(0,0,appalert);
    };

    // menu items
    $scope.addmenuitem = function(selectedmenu){
      $scope.selectedmenu = selectedmenu;
      $scope.menuitemtitle = '';
      $('#menuitemdialog').modal('show');
    };

    $scope.savemenuitem = function(){
      $('#menuitemdialog').modal('hide');
      $('#waitmodal').modal('show');
      serverFactory.getitem(-1,'menuitem',$scope,'gotmenuitem');
    };

    $scope.gotmenuitem = function(data){
      var selectedmenuitem = data;
      selectedmenuitem.title = $scope.menuitemtitle === '' ? $scope.selectedpageitem.title : $scope.menuitemtitle;
      selectedmenuitem.pageitem[0] = $scope.selectedpageitem;
      selectedmenuitem.menu[0] = $scope.selectedmenu;
      serverFactory.insertafter($scope,selectedmenuitem,0,'menuitem','savedmenuitem');
    };

    $scope.savedmenuitem = function(){
      serverFactory.getmenu($scope,'gotmenu');
    };

    $scope.removemenuitem = function(selectedmenuitem){
      $('#waitmodal').modal('show');
      serverFactory.deleteitem(selectedmenuitem.id,'menuitem',$scope,'removedmenuitem');
    };
    $scope.removedmenuitem = function(){
      serverFactory.getmenu($scope,'gotmenu');
    };
    $scope.gotmenu = function(data){
      $scope.sitemenu = data;
      $('#waitmodal').modal('hide');
    };

    // site menu - END

    $scope.badgeLabel = function(){
      return "label label-danger label-as-badge";

    }


  }]);
