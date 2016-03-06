<?php
	//file_put_contents('getstarted.log',1);
  //VERY IMPORTANT
  //these services will NEVER error out.
  //at the service we will stop any errors and send back a good json but packaged with error information
	require_once 'Slim/Slim.php';
	require_once 'dataobjectserver/common/logger.php';
	use Slim\Slim;
	Slim::registerAutoloader();
	$app = new Slim();
	//a single rest API is self-sufficient - so how about the db connection is made at the API level
	//this connection object - held inside a global variable or something of that sort is then available to every method, object that is invokved from the API
	//this ensures that a single connection is opened for the entire duration of the API but no more
	//we can then also (brilliant, this one) make full use of db transactions - we can do a full commit / rollback of everything that happened for the duration of the API

	$app->get('/validateuser/:username/:password/',function($username,$password) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$attr['username'] = $username;
		$attr['password'] = $password;
		// also get related objects (last arg = 1)
		$loginuser = $application->GetObjectsByClassNameAndAttributes('appuser',$attr,null,1);
		$retval = array();
		if (sizeof($loginuser->Items)){
			if ($loginuser->Items[0]->role == 'n'){
				$retval['validuser'] = 0;
				$retval['invalidusermsg'] = 'You have an account with us. But don\'t have any privledges. Please contact the system administrator.';
			}
			else {
				$retval['validuser'] = 1;
				$retval['title'] = $loginuser->Items[0]->name;
				$retval['role'] = $loginuser->Items[0]->role;
				$retval['redirect'] = 'pagemanager';
			}
		}
		else {
			$retval['validuser'] = 0;
			$retval['invalidusermsg'] = 'Invalid user name and password';
		}
		allow_cross_domain_calls();
		echo json_encode($retval);
	});


	$app->get('/getitem/:itemtype/:itemid/',function($itemtype,$itemid) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$itemdetails = $application->GetObjectById($itemtype,$itemid,1);
		allow_cross_domain_calls();
		echo json_encode($itemdetails);
	});

	$app->get('/getitems/:itemtype/',function($itemtype) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$items = $application->GetObjectsByClassName($itemtype);
		allow_cross_domain_calls();
		echo json_encode($items);
	});

	$app->get('/getpageitems/',function() {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$sortby['modifieddate'] = 'desc';
		$items = $application->GetObjectsByClassName('pageitem',$sortby);
		// $items = $sorteditems->GetPageTemplateInfoCollection();
		allow_cross_domain_calls();
		echo json_encode($items->Items);
	});

	$app->get('/getrelatedpages/:itemid/',function($itemid) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$itemdetails = $application->GetObjectById('pageitem',$itemid,1);
		allow_cross_domain_calls();
		echo json_encode($itemdetails->GetRelatedStories());
	});

	$app->get('/getsorteditems/:itemtype/:orderby/:sortorder/',function($itemtype,$orderby,$sortorder) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$sortby[$orderby] = $sortorder;
		$items = $application->GetObjectsByClassName($itemtype,$sortby);
		allow_cross_domain_calls();
		echo json_encode($items);
	});

	$app->get('/getmenu/',function() {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$sortby['position'] = 'asc';
		$menu = $application->GetObjectsByClassName('menu',$sortby,1);
		$menudetails = $menu->GetMenu($application);
		allow_cross_domain_calls();
		echo json_encode($menudetails);
	});

	$app->get('/getemptylayoutcolumns/:colcount/',function($colcount) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$emptylayoutcolumns = array();
		for ($i=0;$i<$colcount;$i++){
			$emptylayoutcolumn = $application->GetObjectById('aggregatecolumn',-1,1);
			$emptylayoutcolumn->position = $i+1;
			array_push($emptylayoutcolumns,$emptylayoutcolumn);
		}
		allow_cross_domain_calls();
		echo json_encode($emptylayoutcolumns);
	});
	$app->get('/getpageitemdetailsdata/:itemtype/:itemid/',function($itemtype,$itemid) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$itemdetails = $application->GetObjectById($itemtype,$itemid,1);
		// TODO this should go into the respective classes?
		// However, there is an issue about simply putting this into the respective constructors
		// this comes from the issue that we can only get related data down to one level
		// this was a performance thing previously. However, we need to look at how to get related data iteratively
		if ($itemtype == 'piaggregate' || $itemtype == 'picontent'){
			for($i=0;$i<sizeof($itemdetails->aggregatecolumn);$i++){
				$aggregatecolumn = $application->GetObjectById('aggregatecolumn',$itemdetails->aggregatecolumn[$i]->id,1);
				for($j=0;$j<sizeof($aggregatecolumn->aggregateitem);$j++){
					$aggregateitem = $application->GetObjectById('aggregateitem',$aggregatecolumn->aggregateitem[$j]->id,1);
					$aggregatecolumn->aggregateitem[$j] = $aggregateitem;
				}
				$itemdetails->aggregatecolumn[$i] = $aggregatecolumn;
			}
		}
		allow_cross_domain_calls();
		echo json_encode($itemdetails);
	});

	$app->get('/publishitem/:pageid/',function($pageid) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$page = $application->GetObjectById('pageitem',$pageid,1);
		$page->Publish();
		allow_cross_domain_calls();
		echo json_encode($page);
	});

	$app->get('/publishall/',function() {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$pageitemcollection = $application->GetObjectsByClassName('pageitem',null,1);
		$pageitemcollection->PublishAll();
		allow_cross_domain_calls();
		echo json_encode('$pageitemcollection');
	});
	$app->get('/publishmenu/',function() {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$sortby['position'] = 'asc';
		$menu = $application->GetObjectsByClassName('menu',$sortby,1);
		$menudetails = $menu->Publish($application);
		allow_cross_domain_calls();
		echo json_encode($menudetails);
	});
	$app->get('/unpublish/:pageid/',function($pageid) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$pageitem = $application->GetObjectById('pageitem',$pageid);
		$pageitem->Unpublish();
		allow_cross_domain_calls();
		echo json_encode($pageitem);
	});

	$app->get('/deleteitem/:itemtype/:itemid/',function($itemtype,$itemid) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$item = $application->GetObjectById($itemtype,$itemid,1);
		$item->Delete();
		allow_cross_domain_calls();
		echo json_encode($item);
	});

	$app->post('/saveitem/:itemtype/',function($itemtype) use ($app) {
		// $logger = new Logger('saveitem');
		// $logger->Write($app->request->post('itemObject'));
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		//cast the json object to a well formed php object based on the data object model
		$itemdetails = $application->GetObjectForJSON(json_decode($app->request->post('itemObject')),$itemtype);
		$itemdetails->Save();
		allow_cross_domain_calls();
		echo json_encode($itemdetails);
	});

	$app->post('/insertafter/:itemtype/:insertafterposition/',function($itemtype,$insertafterposition) use ($app) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		//cast the json object to a well formed php object based on the data object model
		$itemdetails = $application->GetObjectForJSON(json_decode($app->request->post('itemObject')),$itemtype);
		$itemdetails->InsertAfter($insertafterposition);
		allow_cross_domain_calls();
		echo json_encode($itemdetails);
	});

	$app->post('/executesqlquery/',function() use ($app) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		//cast the json object to a well formed php object based on the data object model
		$sqlquery = $app->request->post('sqlquery');
		$object = $application->GetObjectById('menuitem',-1,0);
		$result = $object->ExecuteSQLQuery($sqlquery);
		allow_cross_domain_calls();
		echo json_encode($result);
	});

	//IMPORTANT:
	// the reason why fileupload is not part of the saveitemdetails method is that:
	// the GetObjectForJSON messes up in the file data bytes
	$app->post('/fileupload/',function() use ($app) {
		require_once 'dataobjectserver/application.php';
		$application = Application::getinstance();
		$itemdetails = $application->GetObjectById('asset',-1);
		$itemdetails->Save();
		allow_cross_domain_calls();
		echo json_encode($itemdetails);
	});

	$app->run();


function allow_cross_domain_calls() {

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    //echo "You have CORS!";
}
?>
