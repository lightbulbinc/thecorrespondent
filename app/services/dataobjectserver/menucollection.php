<?php
require_once dirname(__FILE__) . '/common/collectionbase.php';
class menucollection extends collectionbase {
  public function GetMenu($application){
    $menudetails = array();
    for ($i=0;$i<sizeof($this->Items);$i++){
			$menutitle = $this->Items[$i];
			for ($j=0;$j<sizeof($menutitle->menuitem);$j++){
				$menutitle->menuitem[$j] = $application->GetObjectById('menuitem',$menutitle->menuitem[$j]->id,1);
			}
			$menudetails[$i] = $menutitle;
		}
    return $menudetails;
  }
  public function Publish($application){
    $menudetails = array();
    $mainmenu = '';
    $footermenu = '';
		for ($i=0;$i<sizeof($this->Items);$i++){
			$menutitle = $this->Items[$i];
			for ($j=0;$j<sizeof($menutitle->menuitem);$j++){
				$menutitle->menuitem[$j] = $application->GetObjectById('menuitem',$menutitle->menuitem[$j]->id,1);
        $mainmenu .= '<li style="background:#294078; "><a href="' . $menutitle->menuitem[$j]->pageitem[0]->pagename . '">' . $menutitle->menuitem[$j]->pageitem[0]->title . '</a></li>';
        $footermenu .= '<li><a href="' . $menutitle->menuitem[$j]->pageitem[0]->pagename . '">' . $menutitle->menuitem[$j]->pageitem[0]->title . '</a></li>';
			}
			$menudetails[$i] = $menutitle;
		}
    $appmetadata = $application->GetObjectById('appmetadata',1);
    file_put_contents($appmetadata->publishpath . 'includes/' . 'main.menu',$mainmenu);
    file_put_contents($appmetadata->publishpath . 'includes/' . 'footer.menu',$footermenu);
    return $menudetails;
  }
}
?>
