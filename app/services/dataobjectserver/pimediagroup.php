<?php
require_once dirname(__FILE__) . '/common/objectbase.php';
class pimediagroup extends objectbase {
  public function Publish($pageitem) {
    $application = Application::getinstance();
    // in the case of pimediagroup, we need the assets that are part of the media group and not part of page item (parent)
    // so we will need the associated data with "$this"
    $pimediagroup = $application->GetObjectById(get_class($this),$this->id,1);
    // IMPORTANT: the media group template is dependant on the orientation
    $file_contents = file_get_contents('templates/' . get_class($this) . '_' . $this->orientation . '.html');
    $pimediagroup_output = '';
    foreach ($pimediagroup->asset as $asset) {
      $pimediagroup_output .= str_replace('[[media-file]]',$asset->name, $file_contents);
    }
    $appmetadata = $application->GetObjectById('appmetadata',1);
    file_put_contents($appmetadata->publishpath . $pageitem->pagename,$pimediagroup_output);
  }
}
?>
