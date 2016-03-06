<?php
require_once dirname(__FILE__) . '/aggregatebase.php';
class picontent extends aggregatebase {
	public function Publish($pageitem){
		$file_contents = aggregatebase::Publish($pageitem);
    // http://php.net/manual/en/function.date-default-timezone-get.php
    date_default_timezone_set('America/Los_Angeles');
    $application = Application::getinstance();
	  $date = strtotime($pageitem->modifieddate);
  	$file_contents = str_replace('[[page_title]]',$pageitem->title,$file_contents);
  	$file_contents = str_replace('[[month]]',date('F',$date),$file_contents);
  	$file_contents = str_replace('[[date]]',date('j',$date),$file_contents);
  	$file_contents = str_replace('[[time]]',date('H',$date) . ':' . date('i',$date),$file_contents);
  	$file_contents = str_replace('[[year]]',date('Y',$date),$file_contents);
  	$file_contents = str_replace('[[authors]]','The Bureau',$file_contents);
		$file_contents = str_replace('[[pageid]]',$pageitem->id,$file_contents);
		$file_contents = str_replace('[[page-content]]',$this->body,$file_contents);
		$file_contents = str_replace('[[title-image]]',$this->titleimage,$file_contents);


  	// write the output file
  	// let's get the path from the meta data
    $appmetadata = $application->GetObjectById('appmetadata',1);
    file_put_contents($appmetadata->publishpath . $pageitem->pagename,$file_contents);
	}
	public function Publish_column_1($aggregatecolumn,$file_contents){
    foreach ($aggregatecolumn->aggregateitem as $aggregateitem_shallow) {
      $aggregateitem = aggregatebase::getobjectdetails($aggregateitem_shallow);
      // the following check just to make sure that a mediagroup was put in this aggregateitem
      if (sizeof($aggregateitem->pageitem)){
        $aggpageitem = aggregatebase::getobjectdetails($aggregateitem->pageitem[0]);
        $pimediagroup = aggregatebase::getobjectdetails($aggpageitem->{$aggpageitem->pagetemplate[0]->pagetype}[0]);
        $pimediagroup->Publish($aggpageitem);
        $file_contents = str_replace('[[pimediagroup-v-pos-' . $aggregatecolumn->position . ']]',$aggpageitem->pagename,$file_contents);
      }
    }
    return $file_contents;
  }
	public function Publish_column_2($aggregatecolumn,$file_contents){
    foreach ($aggregatecolumn->aggregateitem as $aggregateitem_shallow) {
      $aggregateitem = aggregatebase::getobjectdetails($aggregateitem_shallow);
      // the following check just to make sure that a mediagroup was put in this aggregateitem
      if (sizeof($aggregateitem->pageitem)){
        $aggpageitem = aggregatebase::getobjectdetails($aggregateitem->pageitem[0]);
        $pimediagroup = aggregatebase::getobjectdetails($aggpageitem->{$aggpageitem->pagetemplate[0]->pagetype}[0]);
        $pimediagroup->Publish($aggpageitem);
        $file_contents = str_replace('[[pimediagroup-v-pos-' . $aggregatecolumn->position . ']]',$aggpageitem->pagename,$file_contents);
      }
    }
    return $file_contents;
  }
  public function Publish_column_3($aggregatecolumn,$file_contents){
    foreach ($aggregatecolumn->aggregateitem as $aggregateitem_shallow) {
      $aggregateitem = aggregatebase::getobjectdetails($aggregateitem_shallow);
      // the following check just to make sure that a mediagroup was put in this aggregateitem
      if (sizeof($aggregateitem->pageitem)){
        $aggpageitem = aggregatebase::getobjectdetails($aggregateitem->pageitem[0]);
        $pimediagroup = aggregatebase::getobjectdetails($aggpageitem->{$aggpageitem->pagetemplate[0]->pagetype}[0]);
        $pimediagroup->Publish($aggpageitem);
        $file_contents = str_replace('[[pimediagroup-h-pos-' . $aggregatecolumn->position . ']]',$aggpageitem->pagename,$file_contents);
      }
    }
    return $file_contents;
  }
}
?>
