<?php
require_once dirname(__FILE__) . '/positionbase.php';
class menu extends positionbase {
  public function Delete(){
    // we require this override because the menu object contains the menutitem collection that is a dummy collection
    // the menutitem object is only meant to position the pageitem
    // this means that if we delete a menu object, we should remove the menuitems within that object
    // else these will remain in the system as orphans
    // this is beacuse the Delete method is meant to delete an object and "clear out" it's relations but not remove the related objects
    // which is how is it should be
    // so if an object
    foreach ($this->menuitem as $menuitem){
    	$menuitem->Delete();
    }
    objectbase::Delete();
  }
}
?>
