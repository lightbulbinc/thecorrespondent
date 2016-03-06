<?php
require_once dirname(__FILE__) . '/common/objectbase.php';
class positionbase extends objectbase {
  public function InsertBefore($beforeobjectpos){
    $this->position = $beforeobjectpos;
    require_once 'dataobjectserver/application.php';
    $application = Application::getinstance();
    $sortby['position'] = 'asc';
    $positioneditems = $application->GetObjectsByClassName(get_class($this),$sortby);
    foreach ($positioneditems as $positioneditem) {
      if ($positioneditem->position >=$beforeobjectpos){
        $positioneditem->position += 1;
        $positioneditem->Save();
      }
    }
    objectbase::Save();
  }
  public function InsertAfter($afterobjectpos){
    $this->position = $afterobjectpos + 1;
    require_once 'dataobjectserver/application.php';
    $application = Application::getinstance();
    $sortby['position'] = 'asc';
    $positioneditems = $application->GetObjectsByClassName(get_class($this),$sortby);
    foreach ($positioneditems->Items as $positioneditem) {
      if ($positioneditem->position > $afterobjectpos){
        $positioneditem->position += 1;
        $positioneditem->Save(0);
      }
    }
    objectbase::Save();
  }
}
?>
