<?php

/**
 *
 *	By Jelle Ursem
 *	
 *	Ultra-simple database abstraction class.
 *	Reads settings from a properties file and takes care of executing queries.
 *	You can easily extend this class to add new database types.	
 *	see http://code.google.com/p/pork-dbobject/ for more info
 *
 *	@package Pork
 */


/**
 * dbConnection class
 * Handles database connections and querying/inserting/removal of rows.
 * 
 * @package Pork
 * @author Jelle Ursem
 * @copyright Jelle Ursem 2009
 * @version 2.0
 * @access public
 */
 
class DBException extends Exception { }
class DBConnectException extends DBException { }
class DBQueryException extends DBException { }
 
class dbConnection
{	
	 public  $adapter, $insertID, $log;
	/**
	 * dbConnection::__construct()
	 * Reads settings from the default settings file and creates the connection.
	 * @param String $useAlternative Alternative settings file
	 */
	
	function __construct($instanceName='ProductionDatabase')
	{
		$this->adapter = $this->getAdapter(Settings::Load()->Get($instanceName));
	}

	/**
	 * dbConnection::getInstance()	 
	 * Singleton functionality.
	 * Creates a static instance.
	 * Usage: DbConnection::getInstance($name)->fetchAll("show tables");
	 * @return DbConnection instance
	 */
	public static function getInstance($instanceName=null)
    {
    	if ($instanceName === null)
    		$instanceName = 'ProductionDatabase';
		static $instances = array();
		if (!array_key_exists($instanceName, $instances)) 
		{
		   $instances[$instanceName] = new dbConnection($instanceName);
	   }
	  return $instances[$instanceName];
    }


	/**
	 * dbConnection::getAdapter()
	 *
	 * Returns the correct adapter class for the current database type.
	 *
	 */
	private function getAdapter($connectInfo)
	{

		if(class_exists($connectInfo['dbconnection']."Adapter"))
		{
			$adapterclass = "{$connectInfo['dbconnection']}Adapter";
			$adapter = new $adapterclass($connectInfo);
		}
		else
		{
			die("Adapter not found for database connection {$connectInfo->dbconnection}");
		}
		return($adapter);
	}


	/**
	 * dbConnection::connect()
	 * Creates the actual connection
	 * @return bool did the connection succeed
	 */
	function connect()
	{
		$this->connection = $this->adapter->connect();

		if ($this->connection)	
		{
			return true;
		}
		else
		{
			throw new DBConnectException(get_class($this) . ': Could not connect to db.');
		}
		return false;
	}


	function escapeValue($value)
	{
		return($this->adapter->escapeValue($value));
	}

	/**
	 * dbConnection::numrows()
	 * Find out the number of rows returned
	 * @return int Number of rows
	 */
	function numrows()
	{
		return($this->adapter->numRows($this->result));
	}

		
	/**
	 * dbConnection::query()
	 * Execute the passed query on the database and determine if insert_id or affected_rows or numrows has to be called.
	 * @param String $query Query to be executed.
	 * @returns mixed ID if inserted row, false on error
	 */
	function query($query)
	{
		if($this->adapter->connection == false) {
			throw new DBConnectException(get_class($this) . ': Not connected.');
			return false;
		}
				
		$result = $this->adapter->query($query);
		
		if(!$result)
		{
			$error = $this->adapter->getError();
			if (Config::Get('PROJECT_STATUS') == PROJECT_READY) {
				throw new DBQueryException("Error '{$error}' during execution of query {$query}");
			}
			return false;
		}
		
		$query = trim(strtolower($query));

		$firstpart = substr($query, 0, 6);
		$this->insertID = false;
		$this->affected = false;
		$this->numrows = false;
		switch($firstpart)
		{
				case 'insert':
					$this->insertID = $this->adapter->getInsertID();
				break;
				case 'delete':
				case 'replac':
				case 'update':
					//$this->affected = $this->adapter->numAffected();
				break;
				case 'select':
					$this->numrows = $this->adapter->numRows();
				break;
		}
		if ($this->insertID != false) { return ($this->insertID); }

		return true;
		
	}

	/**
	 * dbConnection::multipleQueries()
	 * Execute multiple passed queries on the database
	 * @param String $sql Queries to be executed.
	 * @returns mixed ID if inserted row, false on error
	 */
	public function multipleQueries($sql, &$errors=null) 
	{
		$queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $sql);
		$result = true;
		foreach ($queries as $query) {
		   if (strlen(trim($query)) > 0) {
		   	   $newRes = $this->query($query);
		   	   if (!$newRes && $errors !== null)
		   	   	   $errors[] = $query;
		   	   $result = $result && $newRes;
		   }
		}
		return $result;
	}

	/**
	 * dbConnection::fetchOne()
	 * Execute the query and return result # 0.
	 * If no query is passed it will use the previous result.
	 * @param $query optional query to execute. 
	 * @returns String $output
	 */
	function fetchOne($query)
	{
		return ($this->adapter->fetchOne($query));
	}

	/**
	 * dbConnection::fetchAll()
	 * Execute the passed query and fetch a multi-dimensional array of results using $func
	 * If no query is passed it will use the previous result.
	 * @param $query optional query to execute. 
	 * @param $func function to use. Can use mysql_fetch_array or mysql_fetch_object or mysql_fetch_assoc at will.
	 * @returns Array|Object $output multi dimensional array of output.
	 */
	function fetchAll($query=false, $type='assoc')
	{
		//echo $query."<br>\n";
		return($this->adapter->fetchAll($query, $type));
	}

	/** 
	 * dbConnection::fetchRow()
	 * Execute the passed query and fetch only one row of results using $func
	 * If no query is passed it will use the previous result.
	 * @param $query optional query to execute. 
	 * @param $func function to use. Can use array or object or assoc at will.
	 * @returns Array|Object $output multi dimensional array of output.
	 */
	function fetchRow($query=false, $type='assoc')
	{
		return ($this->adapter->fetchRow($query, $type));
	}

	/**
	 * dbConnection::setDatabase()
	 * 
	 * @param mixed $val
	 * @return
	 */
	function setDatabase($val)
	{
		$this->adapter->setDatabase($val);
	}

	/**
	 * dbConnection::getError()
	 * Returns the last error from the database connection. 
	 * @return string error
	 */
	function getError()
	{
		return($this->adapter->getError());
	}


	/**
	 * dbConnection::getQueries()
	 * Returns an array with executed SQL queries.
	 * @return array $queries
	 */
	function getQueries()
	{
		return($this->adapter->queries);
	}


	/**
	 * dbConnection::tableExists()
	 * @param string $table talble to check if exists
	 * @return boolean exists
	 */	
	function tableExists($table)
	{
		return($this->adapter->tableExists($table));
	}


	/**
	 * dbConnection::tablePrefix()
	 * @return boolean exists
	 */	
	function tablePrefix()
	{
		return($this->adapter->tablePrefix());
	}


	/**
	 * dbConnection::connectionHash()
	 * @return boolean exists
	 */	
	function connectionHash()
	{
		return($this->adapter->connectionHash());
	}

	
	public function getIndexCreateSQL($index, $table, $ext) {
		return($this->adapter->getIndexCreateSQL($index, $table, $ext));
	}


	public function getEngineSQL($ext) {
		return($this->adapter->getEngineSQL($ext));
	}
	
	
	public function getIndexDropSQL($index, $table) {
		return($this->adapter->getIndexDropSQL($index, $table));
	}
	
	
	public function getIndexesFromTable($table) {
		return($this->adapter->getIndexesFromTable($table));
	}
	
	
	public function getColumns($table) {
		return($this->adapter->getColumns($table));
	}
	
}


/**
 * PDO Adapter
 * 
 * @access public
 */
class PDOAdapter
{
	public $connection;
	public $result;
	public $database;
	public $queries;
	public $dbtype;
	public $prefix;
	public $connectionHash;

	/**
	 * PDOAdapter::__construct()
	 * 
	 * @param mixed $info
	 * @return void
	 */
	public function __construct($info)
	{
		$this->database = $info['database'];
		$this->connection = $this->connect($info['host'], $info['username'], $info['password'], $info['dbtype']);
		$this->queries = array();
		$this->dbtype = $info['dbtype'];
		$this->prefix = $info['tablesprefix'];
		$this->connectionHash = md5($info['host'] . $info['username']);
	}

	/**
	 * PDOAdapter::escapeValue()
	 * 
	 * @param mixed $value
	 * @return
	 */
	public function escapeValue($value)
	{
		return $this->mysql_escape_mimic($value);
		//return mysql_real_escape_string($value);
	}

	/**
	 * Just a little function which mimics the original 
	 * mysql_real_escape_string but which doesn't need an active mysql 
	 * connection. 
	 * Could be implemented as a static function in a database class.
	 * Original: http://www.php.net/manual/en/function.mysql-real-escape-string.php#101248
	 */
	private function mysql_escape_mimic($inp) {
		if(is_array($inp))
			return array_map(__METHOD__, $inp);

		if(!empty($inp) && is_string($inp)) {
		return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
		}

		return $inp;
	}
	
	/**
	 * PDOAdapter::connect()
	 * 
	 * @param mixed $host
	 * @param mixed $username
	 * @param mixed $password
	 * @return
	 */
	public function connect($host, $username, $password, $engine=null)
	{	
		$connectionString = "$engine:host=$host;dbname=" . $this->database;
		$resource = new PDO($connectionString, $username, $password);
		if ($resource)	
		{
			$resource->query('SET NAMES "utf8"');
			return $resource;
		}
		else
		{
			throw new DBConnectException(get_class($this).' : could not connect');
		}
		return false;
	}

	/**
	 * PDOAdapter::query()
	 * 
	 * @param mixed $query
	 * @return
	 */
	public function query($query)
	{
		$this->queries[] = $query;
		//if(!$this->selectDatabase($this->database)) return false;
		$this->result = $this->connection->query($query);
		if (!$this->result && Config::Get('PROJECT_STATUS') == PROJECT_READY) {
			throw new DBQueryException("Error during execution of query {$query}: " . implode("; ", $this->connection->errorInfo()));
		}
		return($this->result);
	}

	/**
	 * PDOAdapter::fetchOne()
	 * 
	 * @param bool $query
	 * @return
	 */
	public function fetchOne($query=false)
	{	
		if ($query != false) $this->query($query);
		if ($this->result != false && ($row = $this->result->fetch(PDO::FETCH_NUM)) && isset($row[0]))
		{
			return $row[0];
		}
		return false;
	}

	/**
	 * PDOAdapter::fetchRow()
	 * 
	 * @param bool $query
	 * @param string $type
	 * @return
	 */
	/**
	 * PDOAdapter::fetchRow()
	 * 
	 * @param bool $query
	 * @param string $type
	 * @return
	 */
	public function fetchRow($query=false, $type='assoc')
	{
		if ($query != false) $this->query($query);
		if ($this->result != false)
		{
			return $this->result->fetch($this->getFetchStyle($type));
		}
		return false;
	}


	private function getFetchStyle($type) {
		switch ($type) {
			default:
			case 'assoc': return PDO::FETCH_ASSOC; break;
			case 'object': return PDO::FETCH_OBJ; break;
			case 'array': return PDO::FETCH_NUM; break;
		}
	}

	/**
	 * PDOAdapter::fetchAll()
	 * 
	 * @param bool $query
	 * @param string $type
	 * @return
	 */
	public function fetchAll($query=false, $type='assoc')
	{
		if ($query != false) $this->query($query);
		if ($this->result !== false)
		{
			$output = array();	
			while ($row = $this->result->fetch($this->getFetchStyle($type)))
			{
				$output[] = $row;
			}
			return $output;
		}
		return false;
	}
	
	/**
	 * PDOAdapter::getInsertID()
	 * 
	 * @return
	 */
	public function getInsertID()
	{
		return $this->connection->lastInsertId();
	}

	/**
	 * PDOAdapter::numRows()
	 * 
	 * @return
	 */
	public function numRows()
	{
		// does not have to work in all databases
		return ($this->result) ? ($this->result->rowCount()) : 0;
	}

	/**
	 * PDOAdapter::numAffected()
	 * 
	 * @return
	 */
	public function numAffected()
	{
		return ($this->result) ? ($this->result->rowCount()) : 0;
	}

	/**
	 * PDOAdapter::selectDatabase()
	 * 
	 * @param mixed $db
	 * @return
	 */
	public function selectDatabase($db)
	{
		return $this->query("USE $db;");
	}

	/**
	 * PDOAdapter::tableExists()
	 * 
	 * @param mixed $table
	 * @return
	 */
	public function tableExists($table)
	{
		try {
			$input = $this->fetchOne("SELECT 1 FROM `{$table}`");
			return true;
		} catch (DBQueryException $e) {
			return false;
		}
	}

	/**
	 * PDOAdapter::getError()
	 * 
	 * @return
	 */
	public function getError()
	{
		return $this->connection->errorCode();
	}
	
	/**
	 * PDOAdapter::tablePrefix()
	 * 
	 * @return
	 */
	public function tablePrefix()
	{
		return $this->prefix;
	}
	
	/**
	 * PDOAdapter::connectionHash()
	 * 
	 * @return
	 */
	public function connectionHash()
	{
		return $this->connectionHash;
	}


	/**
	 * Returns true if table exists in current DB.
	 * @param string $table
	 * @return bool
	 */
	/*public function tableExists($table) {
		$database = dbConnection::getInstance()->adapter->database;
		$query = "SELECT COUNT(*) as count FROM `information_schema`.`TABLES` where `TABLE_NAME` LIKE '$table' AND `TABLE_SCHEMA` LIKE '$database'";
		$tables = dbConnection::getInstance()->fetchRow($query);
		return ($tables && $tables['count']);
	}*/
	
	
	/**
	 * Returns array of columns in the table $table. Columns are instances of class ModelMetaColumn.
	 * @param string $table
	 * @return array of ModelMetaColumn
	 */
	public function getColumns($table) {
		$database = dbConnection::getInstance()->adapter->database;
		$query = "SELECT * FROM `information_schema`.`COLUMNS` where `TABLE_NAME` LIKE '$table' AND `TABLE_SCHEMA` LIKE '$database'";
		$columns = dbConnection::getInstance()->fetchAll($query);
		$columns = $columns ? $columns : array();
		$result = array();
		foreach ($columns as $column) {
			$result[] = new ModelMetaColumn(strtolower($column['COLUMN_NAME']), strtolower(preg_replace('/^\s*(\w+)\W*(.*)$/', '$1', $column['COLUMN_TYPE'])));
		}
		return $result;
	}


	/**
	 * Returns array of indexes in the table $table. Indexes are instances of class ModelMetaIndex.
	 * @param string $table
	 * @return array of ModelMetaIndex
	 */
	public function getIndexesFromTable($table) {
		/* read indexes from db */
		$query = "SHOW INDEX FROM $table";
		$indexes = dbConnection::getInstance()->fetchAll($query);
		$indexes = $indexes ? $indexes : array();

		/* create temp structure:
		  'index_name' => array
		    'columns' => array
		      1 => 'column_name1'
		      2 => 'column_name2'
		    'type' => ModelMetaIndex::INDEX
		    'lengths' => array
		      'column_name2' => 255
		 */
		$tmpIndexes = array();
		foreach ($indexes as $index) {
			if (!strcmp($index['Index_type'], 'FULLTEXT'))
				$indexType = ModelMetaIndex::FULLTEXT;
			elseif (!strcmp($index['Key_name'], 'PRIMARY'))
				$indexType = ModelMetaIndex::PRIMARY;
			elseif (!strcmp($index['Non_unique'], '0'))
				$indexType = ModelMetaIndex::UNIQUE;
			else
				$indexType = ModelMetaIndex::INDEX;
			
			$indexName = $index['Key_name'];
			if (!isset($tmpIndexes[$indexName])) $tmpIndexes[$indexName] = array(
				'columns' => array(), 
				'type' => $indexType,
				'length' => array());
			$tmpIndexes[$indexName]['columns'][(int)$index['Seq_in_index']] = $index['Column_name'];
			$tmpIndexes[$indexName]['lengths'][$index['Column_name']] = $index['Sub_part'];
		}
		
		/* now loop through temporary structure and create true index objects */
		$result = array();
		foreach ($tmpIndexes as $indexName => $index) {
			$result[] = new ModelMetaIndex($index['columns'], $index['type'], $index['lengths'], $indexName);
		}
		
		return $result;
	}
	
	
	/**
	 * Returns SQL to drop index.
	 * @param object $index
	 * @param string $table
	 * @return SQL to drop index.
	 */
	public function getIndexDropSQL($index, $table) {
		return 'DROP INDEX `' . $index->name . '` ON `' . $table . '`;';
	}
	
	
	/**
	 * Returns suffix to specify engine in create table command.
	 * @param bool $ext 
	 * @return string
	 */
	public function getEngineSQL($ext) {
		return $ext ? "ENGINE=MyISAM" : "ENGINE=InnoDB";
	}
	
	
	/**
	 * Returns SQL to create index.
	 * @param object $index
	 * @param string $table
	 * @param bool $ext true if table is extended one
	 * @return SQL to create index.
	 */
	public function getIndexCreateSQL($index, $table, $ext) {
		$tmpColumns = array();
		foreach ($index->columns as $column) {
			$tmpColumns[] = '`' . $column . '`' . (isset($index->lengths[$column]) ? ('(' . $index->lengths[$column] . ')') : '');
		}
		$type = '';
		if ($index->type == ModelMetaIndex::UNIQUE)
			$type = 'UNIQUE';
		elseif ($index->type == ModelMetaIndex::FULLTEXT)
			$type = 'FULLTEXT';
		elseif ($index->type == ModelMetaIndex::PRIMARY)
			return 'ALTER TABLE `' . $table . '` ADD PRIMARY KEY (' . implode(', ', $tmpColumns) . ');';
		if ($type == 'FULLTEXT' && !$ext)
			return '/* Cannot create FULLTEXT INDEX `' . $index->name . '` ON `' . $table . '` because table type doesn\'t support it */';
		return 'CREATE ' . $type . ' INDEX `' . $index->name . '` ON `' . $table . '` (' . implode(', ', $tmpColumns) . ');';
	}
}


?>
