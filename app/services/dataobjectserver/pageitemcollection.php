<?php
require_once dirname(__FILE__) . '/common/collectionbase.php';
class pageitemcollection extends collectionbase {
  // the page items collection that we want to return in most cases, should only have the pageitem data
  // we don't want to pass back to the client the related data
  // but it would be great to get the page type (template) for each page item
  // this is useful information to be able to filter lists by page type (template)
  public function GetPageTemplateInfoCollection(){
    $retval = array();
    foreach ($this->Items as $thisitem) {
      $thisitem->pagetype = $thisitem->pagetemplate[0]->pagetype;
      // now that we have all the info for the page, let's only send back the stuff that we need on the client
      $object_vars = get_object_vars ($thisitem);
      foreach ($object_vars as $key => $value) {
        if (gettype($value) == 'array'){
          unset($thisitem->{$key});
        }
      }
      array_push($retval,$thisitem);
    }
    return $retval;
  }
  public function PublishAll(){
    foreach ($this->Items as $thisitem) {
       $thisitem->Publish();
    }
  }
}
?>
