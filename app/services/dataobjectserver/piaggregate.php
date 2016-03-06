<?php
require_once dirname(__FILE__) . '/aggregatebase.php';
class piaggregate extends aggregatebase {
  public function Publish($pageitem){
    $file_contents = aggregatebase::Publish($pageitem);
    $file_contents = str_replace('[[pageid]]',$pageitem->id,$file_contents);
    // write the output file
  	// let's get the path from the meta data
    $application = Application::getinstance();
    $appmetadata = $application->GetObjectById('appmetadata',1);
    file_put_contents($appmetadata->publishpath . $pageitem->pagename,$file_contents);

  }
  public function Publish_column_1($aggregatecolumn,$file_contents){
    $main_content_left = '';
    $application = Application::getinstance();
    foreach ($aggregatecolumn->aggregateitem as $aggregateitem_shallow) {
      $aggregateitem = aggregatebase::getobjectdetails($aggregateitem_shallow);
      // $aggregateitemtype = $aggregateitem->aggregateitemtype[0];
      if (sizeof($aggregateitem->pageitem)){
        $aggpageitem = aggregatebase::getobjectdetails($aggregateitem->pageitem[0]);
        $picontent = $aggpageitem->{$aggpageitem->pagetemplate[0]->pagetype}[0];
        $main_content_left .= '<div class="article-small-block">';
        $main_content_left .= '<div class="article-header"><h2><a href="' . $aggpageitem->pagename . '">' . $aggpageitem->title . '</a></h2></div>';
        $main_content_left .= '<div class="article-photo">';
        $main_content_left .= '<span class="image-hover">';
        $main_content_left .= '<span class="drop-icons">';
        $main_content_left .= '<span class="icon-block"><a href="' . $aggpageitem->pagename . '" title="Read Article" class="icon-link legatus-tooltip">&nbsp;</a></span>';
        $main_content_left .= '</span>';
        $main_content_left .= '<img src="assets/' . $picontent->titleimage . '" class="setborder" alt="" />';
        $main_content_left .= '</span>';
        $main_content_left .= '</div>';
        $main_content_left .= '<div class="article-content">';
        // small sub-title
        $main_content_left .= '<p>' . substr (strip_tags ($picontent->body),0,350) . '</p>';
        $main_content_left .= '<div class="article-links" align="right">';
        $main_content_left .= '<a href="' . $aggpageitem->pagename . '" class="btn"><span class="icon-text">&#59212;</span> Read more</a>';
        $main_content_left .= '</div>';
        $main_content_left .= '</div>';
        $main_content_left .= '</div>';
        // the following step will ensure that we don't have aggregate pages published without their content pages
        // but the good part is that each page makes sure that it only publishes if it is outdated
        // this means that the modifieddate > publishdate
        // TODO: have commented out the following line becuase it seems to throw up this almost undeciferable error
        // $picontent->Publish($aggpageitem);
      }
      else if (sizeof($aggregateitem->tag)){
        $tag = aggregatebase::getobjectdetails($aggregateitem->tag[0]);
      }
    }
    return str_replace('[[main-content-left]]',$main_content_left,$file_contents);
  }

  public function Publish_column_2($aggregatecolumn,$file_contents){
    $main_content_center = '';
    foreach ($aggregatecolumn->aggregateitem as $aggregateitem_shallow) {
      $aggregateitem = aggregatebase::getobjectdetails($aggregateitem_shallow);
      if (sizeof($aggregateitem->pageitem)){
        $aggpageitem = aggregatebase::getobjectdetails($aggregateitem->pageitem[0]);
        $picontent = $aggpageitem->{$aggpageitem->pagetemplate[0]->pagetype}[0];
        $main_content_center .= '<div class="article-big-block"><div class="article-photo"><span class="image-hover"><span class="drop-icons">';
        $main_content_center .= '<span class="icon-block"><a href="' . $aggpageitem->pagename . '" title="Read Article" class="icon-link legatus-tooltip">&nbsp;</a></span>';
        $main_content_center .= '</span>';
        $main_content_center .= '<img src="assets/' . $picontent->titleimage . '" alt="" width="365" class="setborder" />';
        $main_content_center .= '</span></div>';
        $main_content_center .= '<div class="article-header"><h2><a href="' . $aggpageitem->pagename . '">' . $aggpageitem->title . '</a></h2></div>';
        $main_content_center .= '<div class="article-content">';
        // big sub-title
        $main_content_center .= '<p>' . substr (strip_tags ($picontent->body),0,480) . '</p>';
        $main_content_center .= '<div class="article-links" align="right">';
        $main_content_center .= '<a href="' . $aggpageitem->pagename . '" class="btn"><span class="icon-text">&#59212;</span> Read more</a>';
        $main_content_center .= '</div></div></div>';
        // the following step will ensure that we don't have aggregate pages published without their content pages
        // but the good part is that each page makes sure that it only publishes if it is outdated
        // this means that the modifieddate > publishdate
        // $picontent->Publish($aggpageitem);
      }
      else if (sizeof($aggregateitem->tag)){
        $tag = aggregatebase::getobjectdetails($aggregateitem->tag[0]);
      }
    }
    return str_replace('[[main-content-center]]',$main_content_center,$file_contents);
  }
  public function Publish_column_3($aggregatecolumn,$file_contents){
    foreach ($aggregatecolumn->aggregateitem as $aggregateitem_shallow) {
      $aggregateitem = aggregatebase::getobjectdetails($aggregateitem_shallow);
      // the following check just to make sure that a mediagroup was put in this aggregateitem
      if (sizeof($aggregateitem->pageitem)){
        $aggpageitem = aggregatebase::getobjectdetails($aggregateitem->pageitem[0]);
        $pimediagroup = aggregatebase::getobjectdetails($aggpageitem->{$aggpageitem->pagetemplate[0]->pagetype}[0]);
        // $pimediagroup->Publish($aggpageitem);
        $file_contents = str_replace('[[pimediagroup-v-pos-' . $aggregateitem->position . ']]',$aggpageitem->pagename,$file_contents);
      }
    }
    return $file_contents;
  }
  public function Publish_column_4($aggregatecolumn,$file_contents){
    foreach ($aggregatecolumn->aggregateitem as $aggregateitem_shallow) {
      $aggregateitem = aggregatebase::getobjectdetails($aggregateitem_shallow);
      // the following check just to make sure that a mediagroup was put in this aggregateitem
      if (sizeof($aggregateitem->pageitem)){
        $aggpageitem = aggregatebase::getobjectdetails($aggregateitem->pageitem[0]);
        $pimediagroup = aggregatebase::getobjectdetails($aggpageitem->{$aggpageitem->pagetemplate[0]->pagetype}[0]);
        // $pimediagroup->Publish($aggpageitem);
        $file_contents = str_replace('[[pimediagroup-h-pos-' . $aggregateitem->position . ']]',$aggpageitem->pagename,$file_contents);
      }
    }
    return $file_contents;
  }
}
?>
