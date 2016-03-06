<?php
require_once dirname(__FILE__) . '/common/objectbase.php';
class pageitem extends objectbase {
  public function Save() {
    //manually set the page name
    // if the page name is set once, don't update the name
    if (is_null ($this->pagename)){
      $this->pagename = str_replace(' ', '-', $this->title); // Replaces all spaces with hyphens.
      $this->pagename = preg_replace('/[^A-Za-z0-9\-]/', '', $this->pagename); // Removes special chars.
      $this->pagename = strtolower($this->pagename);
      $this->pagename .= '.php';
    }
    if (!$this->id) {
			$this->createdate = 'now()';
		}
    // $this->title = addslashes($this->title);
		$this->modifieddate = 'now()';
    objectbase::Save();

  }
  public function GetRelatedStories(){
    $application = Application::getinstance();
    // handling the tags
  	// the concept here is that we will get all related stories
  	//for that we will use the tags of this story and find all other stories that have the same tag
  	$related_pageitems = array();
  	foreach ($this->tag as $tag){
  		$classAttrValuePairs['name'] = $tag->name;
  		$related_tags = $application->GetObjectsByClassNameAndAttributes('tag',$classAttrValuePairs,null,1);
  		foreach ($related_tags->Items as $related_tag){
  			foreach ($related_tag->pageitem as $related_tag_pageitem){
  				// first make sure that you haven't got this story from another tag
  				if (!array_key_exists($related_tag_pageitem->id,$related_pageitems)){
  					// and we don't want stories that haven't already been Published
  					if ($related_tag_pageitem->publishdate != null){
  						// we don't want this story in the Other Stories list
  						if ($this->id != $related_tag_pageitem->id){
  							$pageitem_detials = $application->GetObjectById('pageitem',$related_tag_pageitem->id,1);
  							if ($pageitem_detials->pagetemplate[0]->template != 'aggregate'){
  								$related_pageitems[$related_tag_pageitem->id] = $related_tag_pageitem;
  							}
  						}
  					}
  				}
  			}
  		}
  	}
  	// lastly we sort the related page items descending by date
  	usort($related_pageitems,function($storyone,$storytwo){
  		return strcmp($storytwo->modifieddate,$storyone->modifieddate);
  	});
    return $related_pageitems;
  }
  public function Publish()
  {
    // first and foremost, ONLY publish outdated pages (modifieddate > publishdate)
    // for some reason this check seems to be true every time
    // if ($this->modifieddate > $this->publishdate){
      $this->{$this->pagetemplate[0]->pagetype}[0]->Publish($this);
      // finally update this page item with the publish date
      $this->publishdate = 'now()';
      // IMPORTANT: We are not going to do a this->save because we don't want to update the modified date at this time.
      // So, conveniently we will do an object base save
      objectbase::Save();
    // }
  }
}
?>
