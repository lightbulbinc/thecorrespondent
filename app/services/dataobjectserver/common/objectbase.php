<?php
require_once dirname(__FILE__) . '/applicationbase.php';
		require_once dirname(__FILE__) . '/datalayer.php';
class objectbase extends applicationbase {
	public function __construct($id,$getrelatedobjectcollection=0,$row=null,$fields=null) {
		$this->id = $id;
		$datalayer = new DataLayer();
		$datalayer->OpenConnection();
		$datalayer->GetObjectProperties($this,$getrelatedobjectcollection,$row,$fields);
		$datalayer->CloseConnection();
	}

	public function Save($saverelations=1)
	{
	  $datalayer = new DataLayer();
	  $datalayer->OpenConnection();
	  try {
		  $datalayer->SaveObjectProperties($this,$saverelations);
	  }
	  catch (Exception $e) {
		  $this->ServerErr = $e->getMessage();
		  $this->ServerErrNo = $e->getCode();
	  }
	  $datalayer->CloseConnection();
	}
	public function Delete()
	{
	  $datalayer = new DataLayer();
	  $datalayer->OpenConnection();
	  try {
		  $datalayer->DeleteObject($this);
	  }
	  catch (Exception $e) {
		  $this->ServerErr = $e->getMessage();
		  $this->ServerErrNo = $e->getCode();
	  }
	  $datalayer->CloseConnection();
	}
	// convenience SQL method that can be called on any object
	public function ExecuteSQLQuery($quey){
		$datalayer = new DataLayer();
	  $datalayer->OpenConnection();
		$retval = null;
	  try {
		  $retval = $datalayer->ExecuteQuery($quey);
	  }
	  catch (Exception $e) {
		  $this->ServerErr = $e->getMessage();
		  $this->ServerErrNo = $e->getCode();
	  }
	  $datalayer->CloseConnection();
		return $retval;
	}
	public function __toString() {
		$retval = '';
		$object_vars = get_object_vars ($this);
		while ( list ($key, $value) = each ($object_vars) ) {	//nice hash iterator code
			$print_val = $value;
			if (isset($print_val)) {
				$print_val = is_array ($value) ? sizeof($value) : $value;

			}
			else {
				$print_val = 'NULL';
			}
			$retval .= $key . '>> ' . $print_val . "\n";
		}
		return $retval;
	}

	public $ServerErr = '';
	public $ServerErrNo = 0;
	public $ServerErrType = '';
}



?>
