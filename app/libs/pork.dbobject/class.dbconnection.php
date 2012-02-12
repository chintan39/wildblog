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
	public static function getInstance($instanceName='ProductionDatabase')
    {
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
			throw new Exception(get_class($this) . ': Could not connect to db.');
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
			throw new Exception(get_class($this) . ': Not connected.');
			return false;
		}
				
		$result = $this->adapter->query($query);
		
		if(!$result)
		{
			$error = $this->adapter->getError();
			if (Config::Get('PROJECT_STATUS') == PROJECT_READY) {
				throw new Exception("Error '{$error}' during execution of query {$query}");
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


}

/**
 *  dbConnectionAdapter interface.
 *
 *  Defines all the functions a database adapter should have to be working with Pork.dbObject
 * 
 * @package Pork
 * @author Jelle Ursem 
 * @copyright Jelle Ursem 2009
 * @version 1.0
 * @access public
 */
interface dbConnectionAdapter 
{
	public function __construct($info);
	public function connect($host, $username, $password, $engine=null);
	public function escapeValue($value);
	public function fetchOne($query=false);
	public function fetchRow($query=false, $type='assoc');
	public function fetchAll($query=false, $type='assoc');	
	public function getError();    
	public function getInsertID();
	public function numRows();
	public function numAffected();
	public function query($query);
	public function selectDatabase($db);	
	public function tableExists($table);
	public function tablePrefix();
	public function connectionHash();
}

/**
 * MySQLAdapter
 * 
 * @package Pork
 * @author Jelle Ursem 
 * @copyright Jelle Ursem 2009
 * @version 1.0
 * @access public
 */
class MySQLAdapter implements dbConnectionAdapter
{
	public $connection;
	public $result;
	public $database;
	public $queries;
	public $dbtype='mysql';
	public $prefix;
	public $connectionHash;

	/**
	 * MySQLAdapter::__construct()
	 * 
	 * @param mixed $info
	 * @return void
	 */
	public function __construct($info)
	{
		$this->database = $info['database'];
		$this->connection = $this->connect($info['host'], $info['username'], $info['password']);
		$this->prefix = $info['tablesprefix'];
		$this->queries = array();
		$this->connectionHash = md5($info['host'] . $info['username']);
	}

	/**
	 * MySQLAdapter::escapeValue()
	 * 
	 * @param mixed $value
	 * @return
	 */
	public function escapeValue($value)
	{
		return mysql_real_escape_string($value);
	}

	/**
	 * MySQLAdapter::connect()
	 * 
	 * @param mixed $host
	 * @param mixed $username
	 * @param mixed $password
	 * @return
	 */
	public function connect($host, $username, $password, $engine=null)
	{	
		$resource = mysql_connect($host, $username, $password);
		if ($resource)	
		{
			mysql_query('SET NAMES "utf8"');
			return $resource;
		}
		else
		{
			throw new Exception(get_class($this).' : could not connect');
		}
		return false;
	}

	/**
	 * MySQLAdapter::query()
	 * 
	 * @param mixed $query
	 * @return
	 */
	public function query($query)
	{
		$this->queries[] = $query;
		if(!$this->selectDatabase($this->database)) return false;
		$this->result = mysql_query($query, $this->connection);
		if (!$this->result && Config::Get('PROJECT_STATUS') == PROJECT_READY) {
			throw new Exception("Error during execution of query {$query}: " . mysql_error());
		}
		return($this->result);
	}

	/**
	 * MySQLAdapter::fetchOne()
	 * 
	 * @param bool $query
	 * @return
	 */
	public function fetchOne($query=false)
	{	
		if($query != false) $this->query($query);
		if($this->result != false && mysql_num_rows($this->result) > 0 && mysql_num_fields($this->result) > 0)
		{
			return( mysql_result($this->result,0) );
		}
		return false;
	}

	/**
	 * MySQLAdapter::fetchRow()
	 * 
	 * @param bool $query
	 * @param string $type
	 * @return
	 */
	/**
	 * MySQLAdapter::fetchRow()
	 * 
	 * @param bool $query
	 * @param string $type
	 * @return
	 */
	public function fetchRow($query=false, $type='assoc')
	{
		if($query != false) $this->query($query);
		if($this->result != false)
		{
			$func = "mysql_fetch_{$type}";
			return($func($this->result));
		}
		return false;
	}

	/**
	 * MySQLAdapter::fetchAll()
	 * 
	 * @param bool $query
	 * @param string $type
	 * @return
	 */
	public function fetchAll($query=false, $type='assoc')
	{
		if($query != false) $this->query($query);
		if($this->result !== false)
		{
			$func = "mysql_fetch_{$type}";
			$output = array();	
			while ($row = $func($this->result))
			{
				$output[] = $row;
			}
			return $output;
		}
		return false;
	}
	
	/**
	 * MySQLAdapter::getInsertID()
	 * 
	 * @return
	 */
	public function getInsertID()
	{
		return mysql_insert_id($this->connection);
	}

	/**
	 * MySQLAdapter::numRows()
	 * 
	 * @return
	 */
	public function numRows()
	{
		return ($this->result) ? mysql_num_rows($this->result) : 0;
	}

	/**
	 * MySQLAdapter::numAffected()
	 * 
	 * @return
	 */
	public function numAffected()
	{
		return ($this->result) ? @mysql_affected_rows($this->result) : 0;
	}

	/**
	 * MySQLAdapter::selectDatabase()
	 * 
	 * @param mixed $db
	 * @return
	 */
	public function selectDatabase($db)
	{
		return mysql_select_db($db, $this->connection);
	}

	/**
	 * MySQLAdapter::tableExists()
	 * 
	 * @param mixed $table
	 * @return
	 */
	public function tableExists($table)
	{
		$input = $this->fetchOne("SHOW TABLES FROM {$this->database} LIKE '{$table}'");
		return($input != false);
	}

	/**
	 * MySQLAdapter::getError()
	 * 
	 * @return
	 */
	public function getError()
	{
		return mysql_error($this->connection);
	}

	/**
	 * MySQLAdapter::tablePrefix()
	 * 
	 * @return
	 */
	public function tablePrefix()
	{
		return $this->prefix;
	}

	/**
	 * MySQLAdapter::connectionHash()
	 * 
	 * @return
	 */
	public function connectionHash()
	{
		return $this->connectionHash;
	}
}

/**
 * PDO Adapter
 * 
 * @access public
 */
class PDOAdapter implements dbConnectionAdapter
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
			throw new Exception(get_class($this).' : could not connect');
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
			throw new Exception("Error during execution of query {$query}: " . implode("; ", $this->connection->errorInfo()));
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
		$input = $this->fetchOne("SHOW TABLES FROM {$this->database} LIKE '{$table}'");
		return ($input != false);
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
}


/**
 * SQLiteAdapter
 *
 * @package Pork
 * @author Jelle Ursem 
 * @copyright Jelle Ursem 2009
 * @version 1.0
 * @access public
 */
class SQLiteAdapter implements dbConnectionAdapter
{
	public $connection, $result, $database, $queries;
	public $dbtype='sqlite';
	public $prefix;
	public $connectionHash;

	/**
	 * SQLiteAdapter::__construct()
	 * 
	 * @param mixed $info
	 * @return void
	 */
	public function __construct($info)
	{
		$error = false;
		$this->database = $info['database'];
		$this->connection = $this->connect($info['database'], $info['mode'],$error);
		$this->prefix = $info['tablesprefix'];
		$this->connectionHash = md5($info['database']);
		if($error != false)
		{
			echo print_array($error, 'SQLITE connection error');
		}
	}

	/**
	 * SQLiteAdapter::escapeValue()
	 * 
	 * @param mixed $value
	 * @return
	 */
	public function escapeValue($value)
	{
		return sqlite_escape_string($value);
	}


	/**
	 * SQLiteAdapter::connect()
	 * 
	 * @param mixed $host
	 * @param mixed $username
	 * @param mixed $password
	 * @return
	 */
	public function connect($host, $username, $password, $engine=null)
	{	
		return sqlite_open($host, $username, $password);
	}

	/**
	 * SQLiteAdapter::query()
	 * 
	 * @param mixed $query
	 * @return
	 */
	public function query($query)
	{
		$this->result = sqlite_query($query, $this->connection);
		if (!$this->result && Config::Get('PROJECT_STATUS') == PROJECT_READY) {
			throw new Exception("Error during execution of query {$query}");
		}
		return($this->result);
	}

	/**
	 * SQLiteAdapter::fetchOne()
	 * 
	 * @param bool $query
	 * @return
	 */
	public function fetchOne($query=false)
	{	
		if ($query != false) $this->query($query);
		if($this->result !== false)
		{
			return( sqlite_fetch_single($this->result) );
		}
		return false;
	}

	/**
	 * SQLiteAdapter::fetchRow()
	 * 
	 * @param bool $query
	 * @param string $type
	 * @return
	 */
	public function fetchRow($query=false, $type='assoc')
	{
		if ($query != false) $this->query($query);
		if($this->result !== false)
		{
			$func = "sqlite_fetch_{$type}";
			return( $func($this->result, 0));
		}
		return false;
	}

	/**
	 * SQLiteAdapter::fetchAll()
	 * 
	 * @param bool $query
	 * @param string $type
	 * @return
	 */
	public function fetchAll($query=false, $type='assoc')
	{
		if ($query != false) $this->query($query);
		if($this->result !== false)
		{
			if($type == 'assoc') return(sqlite_fetch_all($this->result, SQLITE_ASSOC));
			if($type == 'object') 
			{
				$output = array();	
				while ($row = sqlite_fetch_object($this->result))
				{
					$output[] = $row;
				}
			}
			return $output;
		}
		return false;
	}

	/**
	 * SQLiteAdapter::getInsertID()
	 * 
	 * @return
	 */
	public function getInsertID()
	{
		return sqlite_last_insert_rowid($this->connection);
	}
	
	/**
	 * SQLiteAdapter::numRows()
	 * 
	 * @return
	 */
	public function numRows()
	{
		return ($this->result) ? sqlite_num_rows($this->result) : 0;
	}

	/**
	 * SQLiteAdapter::numAffected()
	 * 
	 * @return
	 */
	public function numAffected()
	{
		//return ($this->result) ? @mysql_affected_rows($this->result) : 0;
		return 0;
	}

	/**
	 * SQLiteAdapter::selectDatabase()
	 * 
	 * @param mixed $db
	 * @return
	 */
	public function selectDatabase($db)
	{
		return $this->connect($db);
	}

	/**
	 * SQLiteAdapter::getError()
	 * 
	 * @return
	 */
	public function getError()
	{
		return sqlite_error_string(sqlite_last_error($this->connection));

	}

	/**
	 * SQLiteAdapter::tableExists()
	 * 
	 * @param mixed $table
	 * @return
	 */
	public function tableExists($table)
	{
		$input = $this->fetchOne("SELECT count(name) FROM sqlite_master WHERE type='table' and name='{$table}'");
		return($input == 1);
	}
	
	/**
	 * SQLiteAdapter::tablePrefix()
	 * 
	 * @return
	 */
	public function tablePrefix()
	{
		return $this->prefix;
	}

	/**
	 * SQLiteAdapter::connectionHash()
	 * 
	 * @return
	 */
	public function connectionHash()
	{
		return $this->connectionHash;
	}
}

?>
