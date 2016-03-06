<?php
require_once dirname(__FILE__) . '/common/objectbase.php';
class aggregatebase extends objectbase {

  public function Save(){
    require_once 'dataobjectserver/application.php';
	   $application = Application::getinstance();
     // piaggregate -> column -> aggregateitem (aggregateitemtype) -> pageitem
    for ($i=0;$i<sizeof($this->aggregatecolumn);$i++){
      //cast the json object to a well formed php object based on the data object model
      $aggregatecolumn = $application->GetObjectForJSON($this->aggregatecolumn[$i],'aggregatecolumn');
      for ($j=0;$j<sizeof($aggregatecolumn->aggregateitem);$j++){
        $aggregateitem = $application->GetObjectForJSON($aggregatecolumn->aggregateitem[$j],'aggregateitem');
        $aggregateitem->position = $j+1;
        if (sizeof($aggregateitem->pageitem)){
        	$aggregateitem->pageitem[0] = $application->GetObjectForJSON($aggregateitem->pageitem,'pageitem');
        }
        else if (sizeof($aggregateitem->tag)){
        	$aggregateitem->tag[0] = $application->GetObjectForJSON($aggregateitem->tag,'tag');
        }
        $aggregateitem->Save();
        $aggregatecolumn->aggregateitem[$j] = $aggregateitem;
      }
      $aggregatecolumn->position = $i+1;
      $aggregatecolumn->Save();
      $this->aggregatecolumn[$i] = $aggregatecolumn;
    }
    objectbase::Save();
  }
  public static function getobjectdetails($object){
    $application = Application::getinstance();
    return $application->GetObjectById(get_class($object),$object->id,1);
  }

  public function Publish($pageitem){
    $file_contents = file_get_contents('templates/' . $pageitem->pagetemplate[0]->pagetype . '.html');
    $file_contents = str_replace('[[page_title]]',$pageitem->title,$file_contents);


    // aggregate pages within this page
  	$piaggregate = aggregatebase::getobjectdetails($this);
    foreach ($piaggregate->aggregatecolumn as $aggregatecolumn_shallow){
      $aggregatecolumn = aggregatebase::getobjectdetails($aggregatecolumn_shallow);
      // the child aggregate class has a set of methods (Publish_column_<n>) to handle each column publish
      // To make tihs work right, you need to ensure that the child aggregate class has the same number of methods as the number
      // of columns defined in the aggreate layout
      // The benefit of using reflection, is that we don't need to keep track (or check for) of the number of columns in the layout
      $file_contents = $this->{'Publish_column_' . $aggregatecolumn->position}($aggregatecolumn,$file_contents);
    }

    return $file_contents;
	}

}
?>
