<?php
/*
    Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 *  
 *	Based on Pork.dObject version 1.3.1 
 *	By Jelle Ursem
 *	see http://code.google.com/p/pork-dbobject/ for more info
 *	
 */
 
require_once(DIR_PACKAGES . PACKAGE_ABSTRACT . DIRECTORY_SEPARATOR . DIR_MODELS . "AbstractBasicModel.php");

define('RELATION_SINGLE', 'RELATION_SINGLE');
define('RELATION_FOREIGN', 'RELATION_FOREIGN');
define('RELATION_MANY', 'RELATION_MANY');
define('RELATION_NOT_RECOGNIZED', 'RELATION_NOT_RECOGNIZED');
define('RELATION_NOT_ANALYZED', 'RELATION_NOT_ANALYZED');
define('RELATION_CUSTOM', 'RELATION_CUSTOM');

define('DEBUG_PRINT_QUERIES', 0);

/**
 *  AbstractDBObjectModel
 * 
 *	A tiny (500 lines) but powerful hot-pluggable OR-mapper/Active Record implementation for PHP5. 
 *	It automatically recognizes different types of relations in your database by matching primary keys and takes care of most of your SQL queries.
 * 
 * @package Pork
 * @author Jelle Ursem
 * @copyright Belfabriek 2009
 * @version 1.3.1
 * @access public
 */
class AbstractDBObjectModel extends AbstractBasicModel
{
	var $fields = array();
	var $primary;
	var $id;
	var $table;
	var $tableBase;
	var $tableExt;
	var $connection;
	var $databaseValues = array();
	var $changedValues;
	var $relations;
	var $orderProperty;
	var $orderDirection;
	var $nonDbProperties=array();
	var $nonDbValues=array();
	var $subItems=array();
	var $extendedTextsSupport=null;		// ability to translate columns
	var $languageSupportAllowed = false;
	var $forceLanguage = false;

    function __construct($id = false) {
    	parent::__construct($id);
		
    	$this->languageSupport = $this->languageSupportAllowed && Environment::getPackage($this->package)->languageSupport;
    }
    
    public function forceLanguage($language) {
    	$this->forceLanguage = $language;
    }
    
    public function getForceLanguage() {
    	return $this->forceLanguage;
    }
    
    public function getLanguage() {
    	if ($this->forceLanguage) {
    		return $this->forceLanguage;
    	}
    	return Language::get();
    }
    
	/** 
	 *	This is  the function you use in the constructor of your objects to map fields to the database 
	 *	@param string $table the database table to hook this class to
	 *	@param array $fields array of fields/property mappings to use in this object
	 *	@param int $primarykey the field to use as primary key
	 *	@param int $id the value of the primary key that will be used to find the current row in the database
	 *  @param string $connection settings connection name to use for this instance.
	 */
	public function __setupDatabase()
	{
		if ($this->extendedTextsSupport === null) {
			$this->extendedTextsSupport = false;
			foreach ($this->fields as $f) {
				if ($this->getMetaData($f)->getExtendedTable()) {
					$this->extendedTextsSupport = true;
				}
			}
		}
		if ($this->extendedTextsSupport) {
			$this->tableExt = $this->tableBase . '_ext';
		}
		$this->databaseValues = array();
		$this->changedValues = array();
		$this->relations = array();
		$this->orderProperty = false;
		$this->orderDirection = false;
		if($this->id) $this->__init();
	}

	/** 
	 *	Fills the current object with the corresponding row from the database. 
	 */
	protected function __init() 
	{
		if ($this->id != false) {
			$fieldnames = implode(", ", $this->getFieldsSQLArray());
			$join = QueryBuilder::getExtendedTextsJoin($this);
			$query = "SELECT 
					{$fieldnames} 
				FROM `{$this->tableBase}`
				$join
				WHERE {$this->tableBase}.`{$this->primary}` = {$this->id}";
				if (DEBUG_PRINT_QUERIES) {
					echo $query."<br>\n<br>\n";
				}
				Benchmark::logQuery($query);
			$input = dbConnection::getInstance($this->connection)->fetchRow($query, 'assoc');
			if ($this->import($input) === false) {
				$input = dbConnection::getInstance($this->connection)->fetchRow($query, 'assoc');
				$this->import($input);
			}
		}
	}

	
	
	public function getFieldsSQLArray($justThese=array()) {
		if (count($justThese)) {
			$fields = &$justThese;
		} else {
			$fields = array_keys($this->fields);
		}
		$fieldnames = array();

		foreach ($fields as $property) {
			if ($this->extendedTextsSupport && $this->getMetaData($property)->getExtendedTable()) {
				$fieldnames[] = $this->tableExt.'.`'.$property . '`';
			} else {
				$fieldnames[] = $this->tableBase.'.`'.$property . '`';
			}
		}
		if ($this->extendedTextsSupport) {
			$fieldnames[] = $this->tableExt.'.lang';
		}
		return $fieldnames;
	}
	
	/** 
	 *	Fills the current object with the corresponding row from the database. 
	 */
	private function loadDefaultLanguage() 
	{
		// default frontend language must be allways set
		if ($this->forceLanguage && $this->forceLanguage != Language::getDefault()) {
			$tmp = $this->forceLanguage;
			$this->forceLanguage = Language::getDefault();
			$this->__init();
			$this->forceLanguage = $tmp;
			$this->Save(true);
		}
	}


	/** 
	 *	Catches the default getter and return the appropriate property
	 *  Checks if the current value is a mapped value, and if so, if it's a changed value or not (due to caching).
	 *	@param string $property the property being called.
	 */
	public function __get($property) { 
		$getMethod = 'get_' . $property;
		if (method_exists($this, $getMethod)) {
			return $this->$getMethod();
		}
		return $this->__getInternal($property);
	}

	protected function __getInternal($field) {
		// TODO: the rest of this method should go to separate method used by __setSomeValue
		if(array_key_exists($field, $this->changedValues)) return($this->changedValues[$field]); // this is an updated property, return it.
		if(array_key_exists($field, $this->databaseValues)) return($this->databaseValues[$field]); // hack to use non-defined
		if(array_key_exists($field, $this->nonDbValues)) return($this->nonDbValues[$field]); // hack to use non-db values
		if($field === false) {
			//TODO: maybe it is possible to return null if no field is found
			// But this is better to control mistakes
			throw new Exception("Tried to get a non-AbstractDBObjectModel property $property for ".get_class($this));
			return false;
		}
	}

	public function __isset ($property) {
		return array_key_exists($property, $this->fields);
	}
	
	/**
	 * TODO: is it needed?
	 * Maybe blank value is sufficient.
	 */
	/*public function __unset ($property) {
	
	}*/
	
	/** 
	 *	Catches the default setter and handles the actions needed.
	 *  Checks if $property is a mapped property, and if so adds the new value to $this->changedValues.
	 *	@param string $property the property being called.
	 *  @param mixed $value the new value to be set.
	 */
	public function __set($property, $value) { // catch the default setter
		$setMethod = 'set_' . $property;
		if(method_exists($this, $setMethod)) {
			return $this->$setMethod($value);
		}
		return $this->__setInternal($property, $value);
	}

	protected function __setInternal($property, $value) { // catch the default setter
		if($this->hasProperty($property)) {
			$this->changedValues[$property] = $value;	
		}
		elseif($this->hasNonDbProperty($property)) {
			$this->nonDbValues[$property] = $value;
		}
		else
		{
			//TODO: maybe it is possible to add nonDbProperties definition automaticly
			// But this is better to control mistakes
			throw new Exception("Tried to set a non-AbstractDBObjectModel property $property for ".get_class($this));
		}
	}

	/**
	 * For serialization
	 */
	public function __sleep() 
	{
		$fields = array_keys(get_object_vars($this));
		return($fields);
	}

	/**
	 * Checks if a certain property is mapped to the database table
	 * @param string $property the property to check
	 * @returns boolean true if found, false if not.
	 */
	public function hasProperty($property) { 
		if (array_key_exists($property, $this->fields) !== false) return true;
		if (array_key_exists($property, $this->databaseValues) !== false ) return true;
		return false;
	}

	/**
	 * Checks if a certain property is mapped to the non-database attributes.
	 * @param string $property the property to check
	 * @returns boolean true if found, false if not.
	 */
	public function hasNonDbProperty($property) { 
		return in_array($property, $this->nonDbProperties);
	}
	
	public function addNonDbProperty($property, $test=true) {
		if ($test && $this->hasProperty($property)) {
			throw new Exception("Property $property already exists in DB.");
		}
		if (!$this->hasNonDbProperty($property)) {
			$this->nonDbProperties[] = $property;
			$this->nonDbValues[$property] = null;
		}
	}

	/**
	 * Tells the database to delete the current mapped row
	 */
	public function DeleteYourself() { //deletes the current object from database.
		
		// delete all related items too (only RELATION_MANY)
		foreach ($this->relations as $model => $relation) {
			if ($relation->relationType == RELATION_MANY) {
				$class = $relation->connectorClass;
				$object = new $class(false);
				$connector = $relation->connectorClass;
				$connector = new $connector(false);
				$propertyThis = ($connector->relations[get_class($this)]->sourceProperty) ? $connector->relations[get_class($this)]->sourceProperty : $connector->fields[$this->primary];
				$input = AbstractDBObjectModel::search($relation->connectorClass, array($propertyThis => $this->id)); // search for all items that use this object
				if($input) { 
					foreach ($input as $i) {
						$i->deleteYourSelf();
					}
				}
			}
		}
		
		// delete the item itself
		$this->invalidateCache();
		if($this->id !== false) {
			dbConnection::getInstance($this->connection)->query("delete from `{$this->tableBase}` where `{$this->primary}` = {$this->id}");
			
			// delete all depending items too
			if ($this->extendedTextsSupport) {
				dbConnection::getInstance($this->connection)->query("delete from `{$this->tableExt}` where `item` = {$this->id}");
			}
		}
	}

	/**
	 * AbstractDBObjectModel::setOrderProperty()
	 * 
	 * Defines the default order to sort Find results by.
	 * 
	 * @param mixed $field field to sort
	 * @param string $order ASC / DESC
	 */
	public function setOrderProperty($field, $order='ASC') { 
		$this->orderProperty = $field;
		$this->orderDirection = $order;
	}

	/**
		Insert this object into the database:
		* prepare the query with just a null value for primary key
		* append the changed fields and (escaped)values of this object if needed
		* execute the query
	*/
	private function InsertNew()
	{
		$insertfields = '';
		$insertValues = '';
		if ($this->languageSupport) {
			$insertfields .= ', ' . 'lang';
			$insertValues .= ', ' . $this->getLanguage();
		}
		$insertfieldsExt = '';
		$insertValuesExt = '';
		if (sizeof($this->changedValues) > 0) { // do we have any new-set values?
			$filteredValues = $this->changedValues;
			foreach (array_keys($filteredValues) as $field) {
				if ($this->extendedTextsSupport && $this->getMetaData($field)->getExtendedTable()) {
					$insertfieldsExt .= ', `' . $field . '`';
				} else {
					$insertfields .= ', `' . $field . '`';
				}
			} 
			foreach ($filteredValues as $property=>$value) { // append each value escaped to the query
				if ($this->extendedTextsSupport && $this->getMetaData($property)->getExtendedTable()) {
					$insertValuesExt .= ", '".dbConnection::getInstance($this->connection)->escapeValue($value)."'";
				} else {
					$insertValues .= ", '".dbConnection::getInstance($this->connection)->escapeValue($value)."'";
				}					
				$this->databaseValues[$property] = $this->changedValues[$property]; // and store it so we don't save it again
			}
			$this->changedValues = array(); // then clear the changedValues 
		}
		$insertfields = $this->primary . $insertfields;
		$insertValues = 'null' . $insertValues;
		$query = "insert into `{$this->tableBase}` ($insertfields) values ($insertValues);";
		if (DEBUG_PRINT_QUERIES) {
			echo $query."<br>\n<br>\n";
		}
		Benchmark::logQuery($query);
		$this->id = dbConnection::getInstance($this->connection)->query($query);
		if ($this->extendedTextsSupport) {
			$insertfieldsExt = '`item`, `lang`' . $insertfieldsExt;
			$tmpInsertValuesExt = $this->id . ', ' . $this->getLanguage() . $insertValuesExt;
			$query = "insert into `{$this->tableExt}` ($insertfieldsExt) values ($tmpInsertValuesExt);";
			if (DEBUG_PRINT_QUERIES) {
				echo $query."<br>\n<br>\n";
			}
			Benchmark::logQuery($query);
			dbConnection::getInstance($this->connection)->query($query);
			foreach (Language::getAll($this->getLanguage()) as $lang) {
				$tmpInsertValuesExt = $this->id . ', ' . $lang['id'] . $insertValuesExt;
				$query = "insert into `{$this->tableExt}` ($insertfieldsExt) values ($tmpInsertValuesExt);";
				if (DEBUG_PRINT_QUERIES) {
					echo $query."<br>\n<br>\n";
				}
				Benchmark::logQuery($query);
				dbConnection::getInstance($this->connection)->query($query);
			}
		}
		$this->databaseValues[$this->primary] = $this->id; // update the primary key
		return($this->id); // and return it 
	}

	protected function adjustValuesBeforeSaving() {
		foreach ($this->getMetaData() as $field => $meta) {
			if ($meta->hasAdjustBeforeSavingMethod()) {
				$adjFunc = 'adjustFunction' . $meta->getAdjustBeforeSavingMethod();
				if (method_exists($this, $adjFunc)) {
					$newValue = $this->$adjFunc($meta, $this->changedValues);
					if ($newValue !== false) {
						$this->$field = $newValue;
					}
				} else {
					throw new Exception(tg("Adjust function ") . $meta->getAdjustBeforeSavingMethod() . tg(" does not exists."));
				}
			}
		}
	}
	
	/**
	 * Updates the current row if $changedValues array is not empty.
	 * If $this->id == false it will insert a new record.
	 * @returns int the newly inserted primary key or current id.
	 */
	public function Save($forceSaving=false) 
	{
		
		$this->adjustValuesBeforeSaving();
		
		if ($this->id == false && ($forceSaving || sizeof($this->changedValues) > 0)) { // it's a new record for the db
			$this->invalidateCache();
			$id = $this->InsertNew();
			$this->analyzeRelations(); // re-analyze the relation types so we can use Find()
			if(array_search('onInsert', get_class_methods(get_class($this))) !== false) { $this->onInsert(); } // fire the onInsert event.			
			return $id;
		}
		elseif ($this->changedValues != false || $forceSaving) { // otherwise just build the update query
			$this->invalidateCache();
			$updateQueryMain = "";
			$updateQueryExt = "";
			if ($forceSaving) {
				$properties = array();
				foreach ($this->getMetaData() as $meta) {
					if (!in_array($meta->getType(), array(Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE)) && $meta->getName() != 'id') {
						$properties[] = $meta->getName();
					}
				}
				$values = &$this->databaseValues;
			} else {
				$properties = array_keys($this->changedValues);
				$values = &$this->changedValues;
			}
			foreach ($properties as $property) {
				$value = &$values[$property];
				if ($this->extendedTextsSupport && $this->getMetaData($property)->getExtendedTable()) {
					$updateQuery = &$updateQueryExt;
				} else {
					$updateQuery = &$updateQueryMain;
				}
				$updateQuery .= $updateQuery ? ', ' : '';
				$updateQuery .= ($value != '') ? " `{$property}` = '".dbConnection::getInstance($this->connection)->escapeValue($value)."'" : "`{$property}` = ''";
				$this->databaseValues[$property] = $value; // store the value so we don't have to save it again
			}
			if ($updateQueryMain) {
				$query = "UPDATE `{$this->tableBase}` SET {$updateQueryMain} WHERE `{$this->primary}` = {$this->id}";
				if (DEBUG_PRINT_QUERIES) {
					echo $query."<br>\n<br>\n";
				}
				Benchmark::logQuery($query);
				dbConnection::getInstance($this->connection)->query($query);
			}
			if ($this->extendedTextsSupport && $updateQueryExt) {
				$query = "UPDATE `{$this->tableExt}` SET {$updateQueryExt} WHERE `item` = {$this->id} AND `lang` = {$this->getLanguage()}";
				if (DEBUG_PRINT_QUERIES) {
					echo $query."<br>\n<br>\n";
				}
				Benchmark::logQuery($query);
				dbConnection::getInstance($this->connection)->query($query);
			}
			$this->changedValues = array(); 
			return($this->id);
		}
		return false;
	}
	
	/**
	 * Could be overloaded.
	 */
	protected function invalidateCache() {
	
	} 
		
	/**
	 * Add a new relation to the relation list and set it to be analyzed if used.
	 * Newly added relations will standard have RELATION_NOT_ANALYZED for relationtype to optimize speed
	 * @param string $classname Connecting classname
	 * @param string $connectorclassname Classname to use as connector class
 	 */
	public function addRelation($classname, $connectorclassname=false) 
	{
		$this->relations[$classname] = new stdClass();
		$this->relations[$classname]->relationType = RELATION_NOT_ANALYZED;
		if($connectorclassname != false) $this->relations[$classname]->connectorClass = $connectorclassname;
		if($this->id != false) $this->analyzeRelations();		
	}


	/**
	 * New function to add the custom relation mappings. Now you no longer need matching primary keys to have a connection.
	 * E.G Map Customers.id to Contracts.Customer_id 
	 *
	 * Usage: $this->addCustomRelation($targetclass, $sourceclassproperty, $targetclassProperty)
	 * Do not forget to do this in both classes. For a relation between a Customers and a Contracts object as shown above, you need to do the following:
	 * //class customer -> __construct()
	 * $this->addCustomRelation('Contract', 'id', 'Customer_id');
	 * // class contract -> __construct()
	 * $this->addCustomRelation('Customer', 'Customer_id', 'id');
	 * All Find() connect and disconnect functions work transparently with this new method. 
	 */
	/**
	 * AbstractDBObjectModel::addCustomRelation()
	 * 
	 * @param mixed $classname
	 * @param mixed $sourceproperty
	 * @param mixed $targetproperty
	 * @return
	 */
	function addCustomRelation($classname, $sourceproperty, $targetproperty, $connectorProperty=null)
	{
		if(!$this->hasProperty($sourceproperty)) {
			throw new Exception("Error in addCustomRelation: ".get_class($this)." hasn't got property ".$sourceproperty.", so couldn't connect to ".$classname);	
		}
		else
		{
			if ($connectorProperty && MetaDataContainer::hasMetaData($this->name, $connectorProperty)) {
				MetaDataContainer::getMetaData($this->name, $connectorProperty)->setOptionsModel($classname);
			} elseif (MetaDataContainer::hasMetaData($this->name, $sourceproperty)) {
				MetaDataContainer::getMetaData($this->name, $sourceproperty)->setOptionsModel($classname);
			}
			$this->relations[$classname] = new stdClass();
			$this->relations[$classname]->relationType = RELATION_CUSTOM;					
			$this->relations[$classname]->sourceProperty = $sourceproperty;
			$this->relations[$classname]->targetProperty = $targetproperty;
		}
	}


	/**
	* New function to add the custom relation mappings with many:many relation.
	 * E.G Map Employee.id to Position.id using EmployeeAtPosition.customer_id and EmployeeAtPosition.position_id
	 *
	 * Usage: $this->addCustomRelationMany($targetclass, $connectorclassname, $sourceclassproperty, $targetclassProperty)
	 * Do not forget to do this in both classes. For a relation between a Employee and a Position object as shown above, you need to do the following:
	 * //class Employee -> __construct()
	 * $this->addCustomRelationMany('Position', 'EmployeeAtPosition', 'employee_id', 'position_id');
	 * // class Position -> __construct()
	 * $this->addCustomRelationMany('Employee', 'EmployeeAtPosition', 'position_id', 'employee_id');
	 * // class EmployeeAtPosition -> __construct()
	 * $this->addCustomRelation('Employee', 'employee_id', 'id');
	 * $this->addCustomRelation('Position', 'position_id', 'id');
	 */
	/**
	 * AbstractDBObjectModel::addCustomRelationMany()
	 * 
	 * @param mixed $classname
	 * @param string $connectorclassname
	 * @param mixed $sourceproperty
	 * @param mixed $targetproperty
	 * @return
	 */
	function addCustomRelationMany($classname, $connectorclassname, $sourceproperty, $targetproperty, $connectorProperty=null)
	{
		if ($connectorProperty && MetaDataContainer::hasMetaData($this->name, $connectorProperty)) {
			MetaDataContainer::getMetaData($this->name, $connectorProperty)->setOptionsModel($classname);
		}
		$this->relations[$classname] = new stdClass();
		$this->relations[$classname]->relationType = RELATION_MANY;
		$this->relations[$classname]->sourceProperty = $sourceproperty;
		$this->relations[$classname]->targetProperty = $targetproperty;
		$this->relations[$classname]->connectorClass = $connectorclassname;
	}


	/** 
	 * This is where the true magic happens. It will analyze what kind of DB relation we're using. (1:1, 1:many, many:many)
	 */
	public function analyzeRelations() 
	{
		foreach($this->relations as $classname=>$info) {
			if(is_subclass_of($classname, 'AbstractDBObjectModel')) {// the class to connect is a AbstractDBObjectModel
				$obj = new $classname(false);
				$info->className = $classname;
				if($info->relationType == RELATION_NOT_ANALYZED)
				{
					if(array_key_exists('connectorClass', get_object_vars($info)) && $info->connectorClass != '' && is_subclass_of($info->connectorClass, 'AbstractDBObjectModel')) { // this class has a connector class. It should be a many:many relation
						$connector = $info->connectorClass;
						$connectorobj = new $connector(false);
						if(array_key_exists($this->primary, $connectorobj->fields) && array_key_exists($obj->primary, $connectorobj->fields)) {
							$info->relationType = RELATION_MANY; // yes! The primary key of the relation now appears in this object, the connector class and one of the connected class. it's a many:many relation
							continue;
						} 
						else { 
							unset($info->connectorClass); // it's not connected to our relations
						}
					}
					if(	array_key_exists($obj->primary, $this->fields) && array_key_exists($this->primary, $obj->fields)) {
						$info->relationType = RELATION_SINGLE; // if the primary key of the connected object exists in this object and the primary key of this object exists in the connected object it's a 1:1 relation
					}
					elseif((array_key_exists($this->primary, $obj->fields) && !array_key_exists($obj->primary, $this->fields) || !array_key_exists($this->primary, $obj->fields) && array_key_exists($obj->primary, $this->fields)) ) {
							$info->relationType = RELATION_FOREIGN;	// if the primary key of the connected object exists in this object (or the other way around), but the primary key of this object does not exist in the connected object (or the other way around) it's a many:1 or 1:many relation		
					}
					elseif($info->relationType == RELATION_NOT_ANALYZED) {
						$info->relationType = RELATION_NOT_RECOGNIZED;  // we don't recognize this type of relation.
						throw new Exception("Warning! Relation not recognized! {$classname} connecting to ".get_class($this)); 
					}
					$this->relations[$classname] = $info;
				}
			}
			else
			{
				throw new Exception("{$classname} is not a AbstractDBObjectModel!");
				unset($this->relations[$classname]); // tried to connect a non-dbobject object.
			}
		}	
	}

	
	/*
		This connects 2 AbstractDBObjectModels together, with a connector class if needed.
	 * Runs relation analyzer if needed.
	 * @uses analyzeRelations
	 * @param object $object the class to connect.
	*/
	/**
	 * AbstractDBObjectModel::Connect()
	 * 
	 * @param mixed $object
	 * @return
	 */
	public function Connect($object) 
	{	
		$this->invalidateCache();
		$className = get_class($object);
		if($this->id == false) $this->Save(); // save both objects if they are new
		if($object instanceof AbstractDBObjectModel && $object->id == false) $object->Save(); 	
		if(array_key_exists($className, $this->relations)) {
			switch($this->relations[$className]->relationType)
			{
				case RELATION_NOT_ANALYZED:
					$this->analyzeRelations(); // if we didn't run the analyzer yet, run it.
					$this->Connect($object); // run connect function again.
				break;
				case RELATION_SINGLE: // link the 2 objects' primary keys
					$this->changedValues[$object->primary] = $object->id;
					$object->changedValues[$this->primary] = $this->id;	 
				break;
				case RELATION_FOREIGN: // determine wich one needs to have the primary key set for the 1:many or many:one relation 
					if(array_key_exists($this->primary, $object->fields)) {
						$object->changedValues[$this->primary] = $this->id;
					}
					elseif(array_key_exists($object->primary, $this->fields)) {
						$this->changedValues[$object->primary] = $object->id;
					}
				break;
				case RELATION_MANY: // create a new connector class, set both primary keys and save it.
					$connector = $this->relations[$className]->connectorClass;
					$connector = new $connector(false);
					$property = ($connector->relations[get_class($this)]->sourceProperty) ? $connector->relations[get_class($this)]->sourceProperty : $connector->fields[$this->primary];
					$connector->$property = $this->id;
					$property = ($connector->relations[get_class($object)]->sourceProperty) ? $connector->relations[get_class($object)]->sourceProperty : $connector->fields[$object->primary];
					$connector->$property = $object->id;
					$connector->Save();
				break;
				case RELATION_CUSTOM:  // determine wich one needs to have the primary key set for the 1:many or many:one relation 
					if($this->relations[$className]->sourceProperty != $this->primary) { // we don't want to change primary keys. This is a good way to check which value to change
						$targetval = $this->relations[$className]->targetProperty;
						$this->changedValues[$this->relations[$className]->sourceProperty] = $object->$targetval;
					}
					else {
						$targetval = $this->relations[$className]->sourceProperty;
						$object->changedValues[$this->relations[$className]->targetProperty] = $this->id;	
					}
				break;
			}
			$this->Save(); // save both objects to store changed values.
			$object->Save();
		}	
	}

	/**
	 * Disconnects the relation between 2 objects.
	 * Runs relation analyzer if needed.
	 * @uses analyzeRelations
	 * @param object $object the class to disconnect.
	 */
	public function Disconnect($object, $id=false) 
	{
		$this->invalidateCache();
		if(!$object && !$id) return;
		if(!$object instanceof AbstractDBObjectModel && $id != false) {
			$object = new $object(false);
			$object->id = $id; // a tweak to disconnect an uninitialized object so that it doesn't have to fetch the whole row first.
		}
		$className = get_class($object);
		if(array_key_exists($className, $this->relations)) {
			switch($this->relations[$className]->relationType)
			{
				case RELATION_SINGLE:
					$this->changedValues[$object->primary] = '';
					$object->changedValues[$this->primary] = '';
				break;
				case RELATION_FOREIGN:
					if(array_key_exists($this->primary, $object->fields)) {
						$object->changedValues[$this->primary] = '';
					}
					elseif(array_key_exists($object->primary, $this->fields)) {
						$this->changedValues[$object->primary] = '';
					}
				break;
				case RELATION_MANY:
					$connector = $this->relations[$className]->connectorClass;
					$connector = new $connector(false);
					$propertyThis = ($connector->relations[get_class($this)]->sourceProperty) ? $connector->relations[get_class($this)]->sourceProperty : $connector->fields[$this->primary];
					$propertyObject = ($connector->relations[get_class($object)]->sourceProperty) ? $connector->relations[get_class($object)]->sourceProperty : $connector->fields[$object->primary];
					$input = AbstractDBObjectModel::search($this->relations[$className]->connectorClass, array($propertyObject => $object->id, $propertyThis => $this->id)); // search for a connector with both primaries
					if($input) $input[0]->deleteYourSelf();
				break;
				case RELATION_CUSTOM:  // determine wich one needs to have the primary key set for the 1:many or many:one relation 
					if($this->relations[$className]->sourceProperty != $this->primary) { // we don't want to change primary keys. This is a good way to check which value to change
						$targetval = $this->relations[$className]->targetProperty;
						$this->changedValues[$this->relations[$className]->sourceProperty] = '';
					}
					else {
						$targetval = $this->relations[$className]->sourceProperty;
						$object->changedValues[$this->relations[$className]->targetProperty] = '';	
					}
				break;
			}
			$this->Save();
			$object->Save();
		}	
	}

	/**
	 * Checks if this is a 'connecting' object between 2 tables by checking if the passed classname is a connection class.
	 * @param string $className Classname to check
	 * @returns boolean
	 */
	private function isConnector($className)
	{
		foreach ($this->relations as $key => $val) { // walk all relations
			if(array_key_exists('connectorClass', get_object_vars($val)) && $val->connectorClass == $className) return true; 
		}
		return false;	
	}

	/**
	 * Imports a pre-filled object (like a table row) into this object
	 * @param array $values Database values to fill this object with
	 * @return bool true if all items exist, false if query should be repeated (new language variant just added)
	 */
	public function Import($values) { 
		$this->id = (!empty($values[$this->primary])) ? $values[$this->primary] : false;
		if ($this->extendedTextsSupport && (!isset($values['lang']) || !$values['lang'])) {
			// the ext_table item does not exist now
			$this->loadDefaultLanguage();
			return false;
		} else {
			$this->databaseValues = $values;
			return true;
		}
	}
	
	/**
	 * Imports a pre-filled settings array to the object.
	 * @param array $values Settings keys/values to fill this object with
	 */
	public function ImportDefaults($array)
	{
		foreach($array as $key=>$val) 
		{
			$this->$key = $val; 
		}
	}


	/**
		Imports an array of e.g. db rows and returns filled instances of $className
		This will not run the analyzerelations or other stuff for performance and recursivity reasons.
	 *  @param string $className ClassName to cast to
	 *  @param array $input recursive array of records to import.
	 *  @return bool true if all items exist, false if query should be repeated (new language variant just added)
	 */
	public static function importArray($className, $input) 
	{
		$output = array();
		if($input != false && sizeof($input) > 0)
		{
			foreach ($input as $array) 
			{
				$elm = new $className(false);
				// if extended text data is just added
				if ($elm->Import($array) === false && $array['id']) {
					$elm = new $className($array['id']);
				}
				if($elm->id != false) $output[] = $elm;
			}
		}
		return(sizeof($output) > 0 ? $output : false);	
	}

	/**
	  * Is the passed class a relation of $this? 
	  * @param $class classname to test
	  * @returns boolean isRelation
	  */
	private function isRelation($class) 
	{
		if (strtolower($class) == strtolower(get_class($this))) { return(get_class($this)); }
		if(!empty($this->relations)){
			foreach($this->relations as $key=>$val) if(strtolower($class) == strtolower($key)) return($key);
		}
		throw new Exception("Error in isRelation! {$class} is not a relation of ".get_class($this));
		return false;
	}

	/**
	The awesome find function. Creates a QueryBuilder Object wich creates a Query to find all objects for your filters.
	 * <code>
	 * //  Syntax for the filters array: 
	 * Array(
 	 *		'id > 500', // just a key element, it will detect this, map the fields and just execute it.
	 *		'property'=> 'value' // $property of $classname has to be $value 
	 *		Array('ClassName'=> array('property'=>'value')// Filter by a (relational) class's property. You can use this recursively!!
	 * ) 
	 * </code>
 	 * @param string $className Classname to find (has to be a relation of $this or get_class($this))
	 * @param array $filters array of filters to use in query
	 * @param array $extra array of eventual order by / group by parameters
	 * @param array $values array of values, that are replace "?" characters
	 * @param array $justThese Fetch only these fields from the table. Useful if you don't want to fetch large text or blob columns.
	 * @uses QueryBuilder to build the actual query
	 * @returns array a batch of pre-filled objects of $className or false if it finds nothing
	 */
	public function Find($className, $filters=array(), $values=array(), $extra=array(), $justThese=array()) 
	{
		$filters = $this->replace($filters, $values);
		$originalClassName = ($className instanceof AbstractDBObjectModel) ? get_class($className) : $className;
		if (!class_exists($originalClassName)) { throw new Exception("Class $originalClassName is not defined.");}
		$class = new $originalClassName();
		if ($originalClassName != get_class($this) && $this->id != false) {
			$filters["id"] = $this->id;
			$filters = array(get_class($this) => $filters);
			$buildOn = new $originalClassName();
			$buildOn->forceLanguage($this->getForceLanguage());
		} else {
			$buildOn = $this;
		}
		$builder = new QueryBuilder($buildOn, $filters, $extra, $justThese);
		$input = dbConnection::getInstance($this->connection)->fetchAll($builder->buildQuery(), 'assoc');
		return(AbstractDBObjectModel::importArray($originalClassName, $input));
	}
	
	/**
	 * AbstractDBObjectModel::findCount()
	 * Finds the number of results that would be fetched for this query.
	 * 
	 * @param string $className Classname to find (has to be a relation of $this or get_class($this))
	 * @param array $filters array of filters to use in query
	 * @param array $extra array of eventual order by / group by parameters
	 * @param array $values array of values, that are replace "?" characters
	 * @param array $justThese Fetch only these fields from the table. Useful if you don't want to fetch large text or blob columns.
	 * @uses QueryBuilder to build the actual query
	 * @return int number of results;
	 */
	function findCount($className, $filters=array(), $values=array(), $extra=array(), $justThese= array())
	{
		$filters = $this->replace($filters, $values);
		$originalClassName = ($className instanceof AbstractDBObjectModel) ? get_class($className) : $className;
		if (!class_exists($originalClassName)) throw new Exception ("Class $originalClassName is not defined.");
		if($originalClassName != get_class($this) && $this->id != false) {
			$filters["id"] = $this->id;
			$filters = array(get_class($this) => $filters);	
			$buildOn = new $originalClassName();
		} else {
			$buildOn = $this;
		}
		$builder = new QueryBuilder($buildOn, $filters, $extra, $justThese);
		return ( $builder->getCount());
	}
	
	/**
	 * Static wrapper around the Find function function. 
	 * @see Find for how this works.
 	 * @param string $className Classname to find (has to be a relation of $this or get_class($this))
	 * @param array $filters array of filters to use in query
	 * @param array $extra array of eventual order by / group by parameters
	 * @param array $justThese Fetch only these fields from the table. Useful if you don't want to fetch large text or blob columns.
	 * @uses Find to build the actual query
	 * @returns array a batch of pre-filled objects of $className or false if it finds nothing
	 */
	static function Search($className, $filters=array(), $values=array(), $extra=array(), $justThese=array())
	{
		$class = new $className();
		if($class instanceOf AbstractDBObjectModel)
		{
			return($class->Find($className, $filters, $values, $extra, $justThese));
		}
	}

	static function SearchCount($className, $filters=array(), $values=array(), $extra=array(), $justThese=array())
	{
		$class = new $className;
		if($class instanceOf AbstractDBObjectModel)
		{
			return($class->FindCount($className, $filters, $values, $extra, $justThese));
		}
	}

	/**
	 * Destructor auto-calls $this->Save().
	 * @uses Save
	 */
	public function __destruct()
	{
		//$this->Save(); // try to save the object if changed.
	}
	
	
	/**
	 * Replaces '?' characters in the input with values from $values
	 *
	 * @param mixed $input input to replace in
	 * @param mixed $values values to replace with
	 *
	 * @return mixed $input with replaced values
	 */
	public function replace($input, &$values, &$i = 0) {
		//print_r(array($input, $values));
		foreach ($input as $k => $v) {
			if ($input[$k] == '') {
				continue;
			}
			if (is_array($input[$k])) {
				$input[$k] = $this->replace($input[$k], $values, $i);
			} else {
				$ex = explode("?", $input[$k]);
				$out = '';
				$ii = 0;
				while ($ii < count($ex)-1) {
					$out .= $ex[$ii];
					$n = (is_numeric($values[$i])) ? $values[$i] : "'" . dbConnection::getInstance($this->connection)->escapeValue($values[$i]) . "'";
					$out .= $n;
					$i++;
					$ii++;
				}
				$out .= $ex[$ii];
				$input[$k] = $out;
			}
		}
		return $input;
	}
	
	
	public function getChanges($suppressedFields=array('updated', 'inserted', 'author')) {
		$changes = array();
		foreach ($this->changedValues as $field => $value) {
			if (in_array($field, $suppressedFields))
				continue;
			if ($value == $this->databaseValues[$field])
				continue;
			
			$change = new BaseChangesModel();
			$change->packagename = $this->package;
			$change->model = $this->getName();
			$change->item = $this->id;
			$change->field = $field;
			
			// long texts are stored as unified diff
			if (in_array($this->getMetaData($field)->getType(), array(Form::FORM_TEXTAREA, Form::FORM_HTML, Form::FORM_HTML_BBCODE))) {
				// Initialize the diff class
				require_once(DIR_LIBS . 'phpdiff/lib/Diff.php');
				require_once(DIR_LIBS . 'phpdiff/lib/Diff/Renderer/Text/Unified.php');
				$diff = new Diff(explode("\n", $this->databaseValues[$field]), explode("\n", $value));
				$renderer = new Diff_Renderer_Text_Unified;
				$change->data = $diff->render($renderer);
			} else { 
				$change->data = "-'{$this->databaseValues[$field]}'\n+'$value'\n";
			}
			
			$changes[] = $change;
		}
		return $changes;
	}
}

/**
 * The helper class that analyzes what joins to use in the select queries 
 * @package Libraries
 */
/**
 * QueryBuilder
 * 
 * @package Pork
 * @author Jelle Ursem
 * @copyright Belfabriek 2009
 * @version 1.0
 * @access public
 */
class QueryBuilder 
{
	var $class, $fields, $filters, $extras, $justthese, $joins, $groups, $wheres, $limit, $orders;

	/**
	 * QueryBuilder::__construct()
	 * 
	 * @param mixed $className
	 * @param mixed $filters
	 * @param mixed $extras
	 * @param mixed $justthese
	 * @return
	 */
	public function __construct($className, $filters=array(), $extras=array(), $justthese=array())
	{
		$this->className = $className;
		$this->filters = $filters;
		$this->extras = $extras;
		$this->wheres = array();
		$this->joins = array();
		$this->fields = array();
		$this->orders = array();
		$this->groups = array();
		if(!($this->className instanceof AbstractDBObjectModel)) $this->className = new $className();
		$tableName = $this->className->tableBase;
		if ($this->className->extendedTextsSupport) {
			$tableNameExt = $this->className->tableExt;
		}
		$this->fields = $this->className->getFieldsSQLArray($justthese);

		$extendedTexts = self::getExtendedTextsJoin($this->className);
		if ($extendedTexts) {
			$this->joins[] = $extendedTexts;
		}
		
		$languageSupport = self::getLanguageWhere($this->className);
		if ($languageSupport) {
			$this->wheres[] = $languageSupport;
		}
		
		if(sizeof($filters) > 0 )
		{
			foreach($filters as $property=>$value) {
				$this->buildFilters($property, $value, $this->className);
			}
		}

		$this->buildOrderBy();
		
	}


	/**
	 * Returns JOIN part of the SQL according to extending texts support (e.g. left join table on ...).
	 * @param object $class Model object to get extending texts support from.
	 * @return string empty string or SQL part
	 */
	static public function getExtendedTextsJoin($class) { 
		if ($class->extendedTextsSupport) {
			return "LEFT JOIN \n\t {$class->tableExt} on {$class->tableExt}.`item` = {$class->tableBase}.`id` AND {$class->tableExt}.`lang` = {$class->getLanguage()}";
		}
		return '';
	}
	
	
	static public function getExtendedTextsFields($class) {
	
	}
	
	/**
	 * Returns WHERE part of the SQL according to language support (e.g. table.lang = '1').
	 * @param object $class Model object to get language support from.
	 * @return string empty string or SQL part
	 */
	static public function getLanguageWhere($class) {
		if ($class->languageSupport) {
			return "{$class->tableBase}.`lang` = {$class->getLanguage()}";
		} else {
			if ($class->languageSupportAllowed) {
				return "{$class->tableBase}.`lang` = 0";
			}
		}
		return '';
	}
	
	/**
	 * QueryBuilder::buildFilters()
	 * This is the tricky part. You can mix both sql wheres as key/values and you can also use a AbstractDBObjectModel class as an array key, then it will auto-join that table.
	 * Syntax then works like this:
	 * 
	 * <pre>
	 * $input = AbstractDBObjectModel::Search('SkillGroupFlowRelation', 
	 *			Array('FlowRouting' =>
	 *				Array("MainTimeframeRelation" => 
	 *					Array("MainRouting"=> 
	 *						Array("SrnMainRelation" => 
	 *							Array("Srn" => Array("id"=>$this->srn->id)))))));
	 * </pre>
	 * This finds a SkillGroupFlowRelation connected to a FlowRouting, which is chained down until an Srn Object with id $this->srn->id.
	 * It automatically generates this query:
	 *
	 * <pre>
	 *	SELECT skillgroup_flow_relation.sf_id, 
	 *		skillgroup_flow_relation.sf_modified, 
	 *		skillgroup_flow_relation.sf_created, 
	 *		skillgroup_flow_relation.sf_flow_id, 
	 *		skillgroup_flow_relation.sf_queue_id, 
	 *		skillgroup_flow_relation.sf_order_pos, 
	 *		skillgroup_flow_relation.sf_description, 
	 *		skillgroup_flow_relation.sf_max_tries, 
	 *		skillgroup_flow_relation.sf_max_ringtime, 
	 *		skillgroup_flow_relation.sf_prompt_wait_start, 
	 *		skillgroup_flow_relation.sf_prompt_wait_between, 
	 *		skillgroup_flow_relation.sf_prompt_silence, 
	 *		skillgroup_flow_relation.sf_target_type, 
	 *		skillgroup_flow_relation.sf_target_id
	 *	 FROM 
	 *		skillgroup_flow_relation
	 *	 LEFT JOIN 
	 *		 flow_routing on skillgroup_flow_relation.sf_flow_id = flow_routing.fr_id
	 *	 LEFT JOIN 
	 *		 main_timeframe_relation on flow_routing.fr_id = main_timeframe_relation.mtr_flow_id
	 *	 LEFT JOIN 
	 *		 main_routing on main_timeframe_relation.mtr_mr_id = main_routing.mr_id
	 *	 LEFT JOIN 
	 *		 srn_main_relation on main_routing.mr_id = srn_main_relation.smr_mr_id
	 *	 LEFT JOIN 
	 *		 srn on srn_main_relation.smr_srn_id = srn.id WHERE srn.id = '134' 
	 *
	 * </pre>
	 *
	 * @param mixed $what what to find: a class or a field in an $class
	 * @param string $value the value that the searchfield needs to have
	 * @param mixed $class the class to find the property in
	 */
	private function buildFilters($what, $value, $class)
	{
		$wtclass = (array_key_exists($what, $class->relations))  ? new $what() :false;

		if($wtclass instanceof AbstractDBObjectModel && is_array($value)) {  // filter by a property of a subclass
			foreach($value as $key=>$val) {
				$this->buildFilters($key, $val, $wtclass);
				$this->buildJoins($wtclass,$class);
			}	
		}
		elseif(is_numeric($what)) { // it's a custom whereclause (not just $field=>$value)		
			if((!$class instanceof AbstractDBObjectModel)) $class = new $class();
			//$value = dbConnection::getInstance($this->className->connection)->escapeValue($value);
			$this->wheres[] = $this->mapFields($value, $class);
		}
		else { // standard $field=>$value whereclause. Prefix with tablename for speed.

			if((!$class instanceof AbstractDBObjectModel)) $class = new $class();
			$value = dbConnection::getInstance($this->className->connection)->escapeValue($value);

			if ($class->extendedTextsSupport && $class->getMetaData($what)->getExtendedTable()) {
				$this->wheres[] = "`{$class->tableExt}.`{$what}` = '{$value}'";
			} else {
				$this->wheres[] = "`{$class->tableBase}`.`{$what}` = '{$value}'";
			}
		}
	}

	/**
	 * QueryBuilder::buildOrderBy()
	 * 
	 * @return
	 */
	private function buildOrderBy()	// filter the 'extras' paramter for order by, group by and limit clauses.
	{
		$hasorderby = false;
		foreach($this->extras as $key=>$extra) {
			if(strpos(strtoupper($extra), 'ORDER BY') !== false) {
				$this->orders[] = $this->mapFields(str_replace('ORDER BY', "", strtoupper($extra)), $this->className);
				unset($this->extras[$key]);
			}
			if(strpos(strtoupper($extra), 'LIMIT') !== false) {
				unset($this->extras[$key]);
				$this->limit = $this->mapFields($extra, $this->className);
			}
			if(strpos(strtoupper($extra), 'GROUP BY') !== false) { 
				$this->groups[] = $this->mapFields(str_replace('GROUP BY', "", strtoupper($extra)), $this->className);
				unset($this->extras[$key]);
			}
		}
		if($this->className->orderProperty && $this->className->orderDirection && sizeof($this->orders) == 0) {
			$this->orders[] = $this->mapFields("{$this->className->orderProperty} ", $this->className).$this->className->orderDirection;
		}
	}

	
	/**
	 * QueryBuilder::mapFields()
	 * 
	 * @param mixed $query
	 * @param mixed $object
	 * @return
	 */
	private function mapFields($query, $object) // map the 'pretty' fieldnames to db table fieldnames.
	{
		$reserved = Array('LIMIT', 'ORDER', 'BY', 'GROUP','DESC','ASC','');
		$words = preg_split("/([\s|\W]+)/", $query, -1, PREG_SPLIT_DELIM_CAPTURE);
		$inQuates = '';
		if(!empty($words)) {
			foreach($words as $key=>$val) { 
				if(strlen(trim($val)) < 2) continue;
				if(array_search(trim(strtoupper($val)), $reserved) !== false) continue;
				// expressions in quates will not be updated
				$pred = isset($words[$key-1]) ? $words[$key-1] : "";
				$pred = strlen($pred) ? $pred[strlen($pred)-1] : "";
				$succ = isset($words[$key+1]) ? $words[$key+1] : "";
				$succ = strlen($succ) ? $succ[0] : "";
				//$pred.=$succ;
				if($pred == "'" || $pred == '"') {
					$inQuates = $pred;
					continue;
				}
				if($inQuates) {
					if ($succ == $inQuates) {
						$inQuates = '';
					}
					continue;
				}
				if(is_numeric($val)) continue;
				//echo "'".$val."'<br />";
				if(strpos($val, '.') !== false) {
					$expl = explode(".", $val);
					if(sizeof($expl) == 2 && $expl[0] == $object->tableBase)  $val = $expl[1];
					else continue;
				}
				if($object->hasProperty($val)) { 
					if ($object->extendedTextsSupport && $object->getMetaData($val)->getExtendedTable()) {
						$words[$key] = '`'.$object->tableExt.'`.`'.$val.'`';
					} else {
						//echo "=".$object->tableBase.'.'.$val."=";
						$words[$key] = '`'.$object->tableBase.'`.`'.$val.'`';
					}
				}
			} 
		}
		return(implode("", $words));
	}

	/**
	 * QueryBuilder::buildJoins()
	 * 
	 * @param mixed $class
	 * @param bool $parent
	 * @return
	 */
	private function buildJoins($class, $parent=false) // determine what joins to use
	{
		if(!$parent) return;	// first do some checks for if we have uninitialized classnames
		if(!($class instanceof AbstractDBObjectModel)) $class = new $class(); 
		$className = get_class($class);
		if(!($parent instanceof AbstractDBObjectModel)) $parent = new $parent();
		switch($parent->relations[$className]->relationType) { // then check the relationtype
			case RELATION_NOT_ANALYZED:							// if its not analyzed, it's new. Save + analyze + re-call this function.
				if(sizeof($class->changedValues) > 0) $class->Save();
				$parent->analyzeRelations();
				return($this->buildJoins($class, $parent));
			break;
			case RELATION_SINGLE:
			case RELATION_FOREIGN:								// it's a foreign relation. Join the appropriate table.
				if($class->hasProperty($parent->primary)) 
				{
					$this->joins[] = "LEFT JOIN \n\t {$class->tableBase} on {$parent->tableBase}.`{$parent->primary}` = {$class->tableBase}.`{$parent->primary}`";
				}
				else if($parent->hasProperty($class->primary)) 
				{
					$this->joins[] = "LEFT JOIN \n\t {$class->tableBase} on {$class->tableBase}.`{$class->primary}` = {$parent->tableBase}.`{$class->primary}`";
				}
			break;
			case RELATION_MANY:									// it's a many:many relation. Join the connector table and then the other one.
				$connectorClass = $parent->relations[$className]->connectorClass;
				$conn = new $connectorClass(false);
				//$this->joins[] = "LEFT JOIN \n\t {$conn->tableBase} on  {$conn->tableBase}.{$parent->primary} = {$parent->tableBase}.{$parent->primary}";
				//$this->joins[] = "LEFT JOIN \n\t {$class->tableBase} on {$conn->tableBase}.{$class->primary} = {$class->tableBase}.{$class->primary}";
				// TODO: doladit - co?
				//echo "<br />\n<br />\n";print_r($parent->relations[$className]);
				$this->joins[] = "LEFT JOIN \n\t {$conn->tableBase} on  {$conn->tableBase}.`{$parent->relations[$className]->sourceProperty}` = {$parent->tableBase}.`{$parent->primary}`";
				$this->joins[] = "LEFT JOIN \n\t {$class->tableBase} on {$conn->tableBase}.`{$parent->relations[$className]->targetProperty}` = {$class->tableBase}.`{$class->primary}`";
				//echo "<br />\n<br />\n";print_r($this->joins);echo "<br />\n<br />\n";
			break;
			case RELATION_CUSTOM:
				$this->joins = array_merge(array("LEFT JOIN \n\t {$class->tableBase} on {$parent->tableBase}.`{$parent->relations[$className]->sourceProperty}` = {$class->tableBase}.`{$parent->relations[$className]->targetProperty}`"), $this->joins);
				
					$this->joins[] = "LEFT JOIN \n\t {$class->tableBase} on {$parent->tableBase}.`{$parent->relations[$className]->sourceProperty}` = {$class->tableBase}.`{$parent->relations[$className]->targetProperty}`";
			break;
			default:
				throw new Exception("Warning! class ".get_class($parent)." probably has no relation defined for class {$className}  or you did something terribly wrong...", $parent->relations[$className]);

			break;
		}		
		$this->joins = array_unique($this->joins);
	}
	
	/**
	 * QueryBuilder::buildQuery()
	 * 
	 * @return
	 */
	public function buildQuery() // joins all the previous stuff together.
	{
		$where = (sizeof($this->wheres) > 0) ? ' WHERE '.implode(" \n AND \n\t", $this->wheres) : '';
		$order = (sizeof($this->orders) > 0) ? ' ORDER BY '.implode(", ", $this->orders) : '' ;
		$group = (sizeof($this->groups) > 0) ? ' GROUP BY '.implode(", ", $this->groups) : '' ;
		$query = 'SELECT '.implode(", \n\t", $this->fields)."\n FROM \n\t".$this->className->tableBase."\n ".implode("\n ", $this->joins)."\n ".$where."\n ".$group."\n ".$order."\n ".$this->limit;
		
		if (Config::Get("DEBUG_MODE")) {
			$t = debug_backtrace();
			$pos = '';
			foreach ($t as $f) {
				// it doesn't work when somewhere in trace is a function outside class 
			    if (isset($f["file"]) && isset($f["line"]) && isset($f["function"])) {
					$file = explode('/', $f["file"]);
					$file = $file[count($file)-1];
					$file = explode('\\', $f["file"]);
					$file = $file[count($file)-1];
					$pos .= "[file:" . $file . "] [method:" . $f["function"] . "] [line:" . $f["line"] . "] \n";
				}
			}
			Benchmark::log("SQL: " . $pos . "\n" . $query); // QUERY logger
		}
		//debug sql
		if (DEBUG_PRINT_QUERIES) {
			echo $query."<br>\n<br>\n";
		}
		Benchmark::logQuery($query);
		return($query);
	}

	/**
	 * QueryBuilder::getCount()
	 * 
	 * @return
	 */
	function getCount()
	{
		$where = (sizeof($this->wheres) > 0) ? ' WHERE '.implode(" \n AND \n\t", $this->wheres) : '';
		$order = (sizeof($this->orders) > 0) ? ' ORDER BY '.implode(", ", $this->orders) : '' ;
		$group = (sizeof($this->groups) > 0) ? ' GROUP BY '.implode(", ", $this->groups) : '' ;
		$query = "SELECT count(*) FROM \n\t".$this->className->tableBase."\n ".implode("\n ", $this->joins)."\n ".$where."\n ".$group."\n ".$order."\n ";
		if (DEBUG_PRINT_QUERIES) {
			echo $query."<br>\n<br>\n";
		}
		Benchmark::logQuery($query);
		//Benchmark::log("SQL: " . $query); // QUERY logger

		return dbConnection::getInstance($this->className->connection)->fetchOne($query);

	}
	
}

?>