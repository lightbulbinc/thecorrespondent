<?php

require_once 'dataobjectserver/application.php';
$application = Application::getinstance();
$logger = new Logger('publishall-logfile');
$logger->Write(0);
$pageitemcollection = $application->GetObjectsByClassName('pageitem',null,1);
$logger->Write(1);
$pageitemcollection->PublishAll();
$logger->Write(3);






?>
