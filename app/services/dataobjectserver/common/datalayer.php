<?php

class DataLayer {
  private $conn = null;
  public function OpenConnection ()
  {
	  $json = json_decode(file_get_contents('dataobjectserver/common/dbmetadata.json'), true);
	  $this->conn = new mysqli(trim($json['dbserver']),trim($json['dbuser']),trim($json['dbpwd']),trim($json['dbname']));
  }
  public function CloseConnection()
  {
    $this->conn->Close();
  }
  public function GetObjectCollection($object,$className,$classAttrValuePairs=null,$sortby=null,$getrelatedobjectcollection=0) {
    $select_sql = "select * from $className";
  	if ($classAttrValuePairs) {
  		$select_sql .= ' where ';
  		foreach ($classAttrValuePairs as $key => $value) {
  			$select_sql .= "$key = '$value' and ";
  		}
  		$select_sql = rtrim($select_sql,'and ');	//strip out the last appended add
  	}
  	if ($sortby) {
  		$select_sql .= ' order by ';
  		foreach ($sortby as $key => $value) {
  			$select_sql .= "$key $value,";
  		}
  		$select_sql = rtrim($select_sql,',');	//strip out the last appended comma
  	}
  	if ($result = $this->conn->query($select_sql)){
  		while ($row = $result->fetch_array()){
  			//the object constructor also takes an argument to get the related object collections
  			//we can possibly create another method to get the object collection along with the related object collections
  			array_push($object->Items,new $className($row['id'],$getrelatedobjectcollection,$row,$result->fetch_fields()));
  		}
  		$object->Length = sizeof($object->Items);
  	}
    else {
      throw new Exception ($this->conn->error);
    }

  }
  public function GetObjectProperties($object,$getrelatedobjectcollection=0,$row=null,$fields=null)
  {
    $id = $object->id ?: 'null';  //if else super shortcut
    if (is_null($row)){
      $select_sql = 'select * from ' . get_class($object) . ' where id = ' . $id;
      $result = $this->conn->query($select_sql);
      $row = $result->fetch_assoc();
      $fields = $result->fetch_fields();
    }
    foreach ($fields as $fieldinfo) {
      $object->{$fieldinfo->name} = $row[$fieldinfo->name];
    }
    if ($getrelatedobjectcollection) {
      $this->getRelatedObjectCollections($object);
    }
  }
  private function getRelatedObjectCollections($object) {
	  $id = $object->id ?: 'null';
	  //get the mapping tables
	  $mapping_tables = DataLayer::getMappingTables($object,$this->conn);
	  foreach ($mapping_tables as $mapping_table_name) {
		  $relatedTablesInfo = DataLayer::getRelatedTablesInfo($object,$mapping_table_name,$this->conn);

      $this_table_name = $relatedTablesInfo['this_table_name'];
      $this_table_fk_col_name = $relatedTablesInfo['this_table_fk_col_name'];
      $related_table_name = $relatedTablesInfo['related_table_name'];
      $related_table_fk_col_name = $relatedTablesInfo['related_table_fk_col_name'];

      $select_sql = "select related_table.* from " . $related_table_name  . " related_table," . $this_table_name  . " this_table," . $mapping_table_name . " where related_table.id = " . $mapping_table_name . "." . $related_table_fk_col_name . " and this_table.id = " . $mapping_table_name . "." .$this_table_fk_col_name . " and this_table.id = " . $id . " group by related_table.id ;";
		  if ($related_id_result = $this->conn->query($select_sql)) {
			  //create the related object collection property
			  //even if there are not defined relateds
			  //this way we at leastr get an empty array
			  require_once 'dataobjectserver/' . $related_table_name . '.php';
			  $className = $related_table_name;
			  $object->{$className} = array();
			  if ($related_id_result->num_rows) {
				  while ($row = $related_id_result->fetch_assoc()) {
					  //now when we call GetObjectProperties we will not request for related objects
					  //this means that we will only go down one level when creating objects
					  //this will prevent going too t
					  //but more importantly, avoid going into an infinite loop for co-related objects
					  $instance = new $className($row['id'],0,$row,$related_id_result->fetch_fields());
					  array_push($object->{$className},$instance);
				  }
			  }
		  }
      else {
  			//throw new Exception ($this->conn->error);
  		}
	  }
  }

  private static function getRelatedTablesInfo($object,$mapping_table_name,$conn) {
    $this_table_name = get_class($object);
    $this_table_fk_col_name = '';
    $related_table_name = '';
    $related_table_fk_col_name = '';
	  $select_sql = "select referenced_table_name, column_name,referenced_column_name from information_schema.key_column_usage where table_name = '" . $mapping_table_name . "' and referenced_table_name is not null and constraint_schema = '" . $conn->query('select database() as thisdb')->fetch_assoc()['thisdb'] . "';";
    $rows = array();
    $this_table_name_found = 0;
	  if ($result = $conn->query($select_sql)) {
		  while ($row = $result->fetch_assoc()) {
        array_push($rows,$row);
      }
	  }
    //let's simply take one row at a time. we know there will ALWAYS be two rows
    //first rows[0]
    if ($rows[0]['referenced_table_name'] == $this_table_name) {
      $this_table_fk_col_name = $rows[0]['column_name'];
      $related_table_name = $rows[1]['referenced_table_name'];
      $related_table_fk_col_name = $rows[1]['column_name'];
    }
    else {
      $this_table_fk_col_name = $rows[1]['column_name'];
      $related_table_name = $rows[0]['referenced_table_name'];
      $related_table_fk_col_name = $rows[0]['column_name'];
    }


    // $retval['this_table_fk_col_name'] = $rows[$this_table_name]['column_name'];

    $retval['this_table_name'] = $this_table_name;
    $retval['this_table_fk_col_name'] = $this_table_fk_col_name;
    $retval['related_table_name'] = $related_table_name;
    $retval['related_table_fk_col_name'] = $related_table_fk_col_name;


    return $retval;
  }

  public function SaveObjectProperties($object,$setRelatedObjects=1)
  {
    $id = $object->id ?: 'null';  //if else super shortcut
    $this_table_name = get_class($object);
    $select_sql = 'select * from ' . $this_table_name . ' where id = ' . $id;
    if ($result = $this->conn->query($select_sql)) {
      $columns = array();

  		while ($fieldinfo = $result->fetch_field()) {
        // since the id field is an auto_increment field, we will not include that in the insert update statement
        if ($fieldinfo->name != 'id') {
          array_push($columns, $fieldinfo);
        }
  		}
      // we are going to split the insert and update statements
      // we found out that a single insert update on duplicate is quite pretty but has one major drawback
      // IT DOES NOT REPORT unique key contriant breaks
      $execute_query = '';
      if ($id == 'null') {  // insert
        $execute_query = 'insert into ' . $this_table_name . '(';
    		for ($i=0;$i<sizeof($columns);$i++) {
    			$execute_query .=  $columns[$i]->name;
    			if ($i < sizeof($columns) - 1) {
    				$execute_query .= ',';
    			}
    		}
    		$execute_query .= ')';
    		$execute_query .= ' values(';
    		for ($i=0;$i<sizeof($columns);$i++) {
    			$execute_query .= DataLayer::prettifydatavalue($object->{$columns[$i]->name},$columns[$i],$this->conn);
    			if ($i < sizeof($columns) - 1) {
    				$execute_query .= ',';
    			}
    		}
        $execute_query .= ');';
      }
      else {
        $execute_query = 'update ' . $this_table_name . ' set ';
        for ($i=0;$i<sizeof($columns);$i++) {
    			$execute_query .= $columns[$i]->name . ' = ' . DataLayer::prettifydatavalue($object->{$columns[$i]->name},$columns[$i],$this->conn);
    			if ($i < sizeof($columns) - 1) {
    				$execute_query .= ',';
    			}
    		}
        $execute_query .= ' where id = ' . $id;
      }
  		if ($this->conn->query($execute_query)) {
  			$object->id = $this->conn->insert_id == 0 ? $object->id : $this->conn->insert_id;
  		}
  		else {
  			throw new Exception ($this->conn->error);
  		}
      if ($setRelatedObjects == 1) {
        $this->setRelatedObjectCollections($object);
      }

	}
}

  private function setRelatedObjectCollections($object) {
	  $this_table_name = get_class($object);
	  //get the mapping tables
	  $mapping_tables = DataLayer::getMappingTables($object,$this->conn);
	  foreach ($mapping_tables as $mapping_table_name) {
		  $this_table_fk_col_name = '';
		  $related_table_name = '';
		  $related_table_fk_col_name = '';
		  //from each mapping table, get the related table info
      $relatedTablesInfo = DataLayer::getRelatedTablesInfo($object,$mapping_table_name,$this->conn);

      $this_table_name = $relatedTablesInfo['this_table_name'];
      $this_table_fk_col_name = $relatedTablesInfo['this_table_fk_col_name'];
      $related_table_name = $relatedTablesInfo['related_table_name'];
      $related_table_fk_col_name = $relatedTablesInfo['related_table_fk_col_name'];

		  if ($related_table_name) {
			  //now there's no way to know from the current related ids if the user has deleted a related object
			  //so right now the only way we can think of to remove all entries for the main table id
			  $clear_relations = "delete from $mapping_table_name where $this_table_fk_col_name = $object->id;";
			  if ($this->conn->query($clear_relations)) {
				//then re-insert the relations. for all relations
				  foreach ($object->{$related_table_name} as $relatedObject) {
					  $update_relations = "insert into $mapping_table_name ($this_table_fk_col_name,$related_table_fk_col_name) values($object->id,$relatedObject->id);";
					  if ($this->conn->query($update_relations)) {
					  }
					  else {
						  throw new Exception($this->conn->error,$this->conn->errno);
					  }
				  }
			  }
			  else {
				  throw new Exception($this->conn->error,$this->conn->errno);
			  }
		  }
	  }
  }
  public function DeleteObject($object)
  {
    $this->clearRelatedObjectCollections($object);
    $delete_sql = 'delete from ' . get_class($object) . ' where id = ' . $object->id;
    if ($this->conn->query($delete_sql)){
    }
    else {
      throw new Exception($this->conn->error,$this->conn->errno);
    }
  }
  private function clearRelatedObjectCollections($object) {
	  $this_table_name = get_class($object);
	  //get the mapping tables
	  $mapping_tables = DataLayer::getMappingTables($object,$this->conn);
	  foreach ($mapping_tables as $mapping_table_name) {
		  $this_table_fk_col_name = '';
		  $related_table_name = '';
		  $related_table_fk_col_name = '';
		  //from each mapping table, get the related table info
      $relatedTablesInfo = DataLayer::getRelatedTablesInfo($object,$mapping_table_name,$this->conn);

      $this_table_name = $relatedTablesInfo['this_table_name'];
      $this_table_fk_col_name = $relatedTablesInfo['this_table_fk_col_name'];
      $related_table_name = $relatedTablesInfo['related_table_name'];
      $related_table_fk_col_name = $relatedTablesInfo['related_table_fk_col_name'];

		  if ($related_table_name) {
			  //now there's no way to know from the current related ids if the user has deleted a related object
			  //so right now the only way we can think of to remove all entries for the main table id
			  $clear_relations = "delete from $mapping_table_name where $this_table_fk_col_name = $object->id;";
			  if ($this->conn->query($clear_relations)) {
			  }
			  else {
				  throw new Exception($this->conn->error,$this->conn->errno);
			  }
		  }
	  }
  }

  public function ExecuteQuery($query){
    $query = str_replace('"','',$query);
    $query = str_replace('\n','',$query);
    $retval = array();
    if ($result = $this->conn->query($query)){
      if (gettype($result) == 'object'){
        while ($row = $result->fetch_assoc()) {
          array_push($retval,$row);
        }
      }
      else {
        $retval['affectedrows'] = 'rows updated';

      }
    }
    return $retval;
  }

  private static function fieldisnotnumeric($fieldinfo) {
	  return $fieldinfo->type != 1 && $fieldinfo->type != 2 && $fieldinfo->type != 3 &&
				$fieldinfo->type != 4 && $fieldinfo->type != 5 && $fieldinfo->type != 8 &&
				$fieldinfo->type != 9 && $fieldinfo->type != 246;
  }
  private static function prettifydatavalue($value,$fieldinfo,$conn) {
    // tinyint_    1
    // boolean_    1
    // smallint_    2
    // int_        3
    // float_        4
    // double_        5
    // real_        5
    // timestamp_    7
    // bigint_        8
    // serial        8
    // mediumint_    9
    // date_        10
    // time_        11
    // datetime_    12
    // year_        13
    // bit_        16
    // decimal_    246
    // text_        252
    // tinytext_    252
    // mediumtext_    252
    // longtext_    252
    // tinyblob_    252
    // mediumblob_    252
    // blob_        252
    // longblob_    252
    // varchar_    253
    // varbinary_    253
    // char_        254
    // binary_        25

    $retval = $value;
    if (is_null($value)) {
  		$retval = 'null';
  	}
    else {
  		if (DataLayer::fieldisnotnumeric($fieldinfo)) {
        //timestamp - 7
        //datetime - 12
  			if (($fieldinfo->type == 7 || $fieldinfo->type == 12) && $value == 'now()') {
  				$retval = $value;
  			}
  			else {
          if ($fieldinfo->type == 253){
            $value = $conn->real_escape_string($value);
          }
  				$retval = "'" . $value . "'";
  			}
  		}
  	}
  	return $retval;

  }
  private static function getMappingTables($object,$conn) {
	  $select_sql = "select table_name from information_schema.key_column_usage where referenced_table_name = '" . get_class($object) . "' and constraint_schema = '" . $conn->query('select database() as thisdb')->fetch_assoc()['thisdb'] . "';";
	  $mapping_tables = array();
	  if ($result = $conn->query($select_sql)) {
		  while ($row = $result->fetch_assoc()) {
        array_push($mapping_tables,$row['table_name']);
		  }
	  }
	  return $mapping_tables;
  }
}

?>
