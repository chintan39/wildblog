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


class BaseDatabaseModel extends AbstractVirtualModel {
	
	var $package='Base';
	var $icon='database';

        /**
         *
         * @param array $itemCollectionIdentifier
         * @param <array> $modelName
         * @param <array> $filters
         * @param <type> $values
         * @param <type> $extra
         * @param <type> $justThese
         * @param <type> $order
         * @param <type> $limit
         * @return <type>
         */
	public function getCollectionItems() {
		$list = array();
		$list['items'] = array();
		$list['columns'] = array('id', 'model', 'table', 'columns');
		foreach (Environment::getPackages() as $package) {
			$package->loadModels();
			foreach ($package->getModels() as $modelName) {
				$model = new $modelName();
				if ($model instanceof AbstractDBObjectModel) {
					$item = new BaseDatabaseTableModel();
					$item->id = $item->model = $modelName;
					if (array_key_exists('table', get_object_vars($model)) && $model->tableBase) {	// if the model has table defined
						$item->tableBase = $model->tableBase;
						try {
							$item->columns = $model->FindCount($modelName);
						} catch (Exception $e) {
							$item->columns = -1;
						}
					} else {				// the object has no table defined
						$item->tableBase = 'no table specified';
						$item->columns = 'no table specified';
					}
					$list['items'][] = $item;
				}
			}
		}
		return $list;
	}
	
	public function getVisibleColumnsInCollection($collectionIdentifier) {
		return array('id', 'model', 'table', 'columns');
	}
	
	static public function getSortable() {
		return null;
	}

	/**
	 * Generates and returns the construct SQL for the whole database.
	 * @return string Construct SQL of the database
	 */
	static public function getConstructSQL($testDatabase=false) {
		$tmp = new self();
		$items = $tmp->getCollectionItems();
		$checkSQL = '';
		foreach ($items['items'] as $dmItem) {
			$checkSQL .= self::getCheckTable($dmItem->id, false, $testDatabase);
		}
		return $checkSQL;
		/*
		$tmp = new self();
		$items = $tmp->getCollectionItems();
		$constructSQL = '';
		foreach ($items['items'] as $dmItem) {
			$constructSQL .= self::getConstructTable($dmItem->id, null, $testDatabase);
		}
		return $constructSQL;
		*/
	}
	

	/**
	 * Generates and returns the construct SQL for the whole database.
	 * @return string Construct SQL of the database
	 */
	static public function getAllModels() {
		$models = array();
		foreach (self::getAllModelNames() as $modelName) {
			$model = new $modelName();
			$models[] = $model;
		}
		return $models;
	}
	
	/**
	 * Generates and returns the construct SQL for the whole database.
	 * @return string Construct SQL of the database
	 */
	static public function getAllModelNames() {
		$tmp = new self();
		$tables = $tmp->getCollectionItems();
		$models = array();
		foreach ($tables['items'] as $dmItem) {
			$modelName = $dmItem->id;
			if (!class_exists($modelName)) 
				throw new Exception("Class '$modelName' doesn't exists.");
			$models[] = $modelName;
		}
		return $models;
	}
	
	/**
	 * Generates and returns the construct SQL for the whole database.
	 * @return string Construct SQL of the database
	 */
	static public function cleanTestTables() {
		$res = true;
		foreach (self::getAllModels() as $model) {
			$tableName = dbConnection::getInstance('TestDatabase')->tablePrefix() . $model->getTableName(false);
			$res &= dbConnection::getInstance('TestDatabase')->query("TRUNCATE TABLE $tableName");
			if ($model->extendedTextsSupport) {
				$tableName = dbConnection::getInstance('TestDatabase')->tablePrefix() . $model->getTableExtName(false);
				$res &= dbConnection::getInstance('TestDatabase')->query("TRUNCATE TABLE $tableName");
			}
		}
		return $res;
	}
	

	/**
	 * Generates and returns the construct SQL for the whole database.
	 * @return string Construct SQL of the database
	 */
	static public function copyTestTables(&$inserted) {
		$res = true;
		$inserted = 0;
		foreach (self::getAllModels() as $model) {
			if (!$model->useInInitDatabase)
				continue;
			$tableNameProd = dbConnection::getInstance()->tablePrefix() . $model->getTableName(false);
			$tableNameTest = dbConnection::getInstance('TestDatabase')->tablePrefix() . $model->getTableName(false);
			$tableNameProdExt = dbConnection::getInstance()->tablePrefix() . $model->getTableExtName(false);
			$tableNameTestExt = dbConnection::getInstance('TestDatabase')->tablePrefix() . $model->getTableExtName(false);
			for ($ext = 0; $ext <= ($model->extendedTextsSupport ? 1 : 0); ++$ext) {
				dbConnection::getInstance()->query("SELECT * FROM " . ($ext ? $tableNameProdExt : $tableNameProd));
				while ($row = dbConnection::getInstance()->fetchRow()) {
					$sql = "INSERT INTO `" . ($ext ? $tableNameTestExt : $tableNameTest) . "` (";
					$columns = array();
					$values = array();
					foreach ($row as $column => $value) {
						$columns[] = "`" . $column . "`";
						$values[] = "'" . mysql_real_escape_string($value) . "'";
					}
					$sql .= implode(", ", $columns);
					$sql .= ') values (';
					$sql .= implode(", ", $values);
					$sql .= ')';
					$resTmp = dbConnection::getInstance('TestDatabase')->query($sql);
					$res &= $resTmp;
					if ($resTmp)
						++$inserted;
				}
			}
		}
		return $res;
	}
	

	/**
	 * Generates and returns the construct SQL for the whole database.
	 * @return string Construct SQL of the database
	 */
	static public function countTestTables($database='ProductionDatabase') {
		$rows = 0;
		foreach (self::getAllModels() as $model) {
			if (!$model->useInInitDatabase)
				continue;
			$tableNameTest = dbConnection::getInstance($database)->tablePrefix() . $model->getTableName(false);
			$rows += dbConnection::getInstance($database)->fetchOne("SELECT COUNT(*) FROM $tableNameTest");
			if ($model->extendedTextsSupport) {
				$tableNameTest = dbConnection::getInstance($database)->tablePrefix() . $model->getTableExtName(false);
				$rows += dbConnection::getInstance($database)->fetchOne("SELECT COUNT(*) FROM $tableNameTest");
			}
		}
		return $rows;
	}
	

	/**
	 * Generates and returns the construct SQL for the whole database.
	 * @return string Construct SQL of the database
	 */
	static public function createTestTables(&$errors) {
		$constructSQL = self::getConstructSQL(true);
		self::doMultipleQueries($constructSQL, $errors, true);
		return count($errors) > 0; 
	}
	

	static private function getCheckTable($modelName, $checkExisting=true, $testDatabase=false) {
		$model = new $modelName();
		$text = '';
		if (array_key_exists("table", get_object_vars($model)) && $model->tableBase && $model->useInInitDatabase) {	// if the model has table defined
			$table = dbConnection::getInstance($testDatabase ? 'TestDatabase' : null)->tablePrefix() . $model->getTableName(false);
			if ($checkExisting && dbConnection::getInstance()->tableExists($table)) {
				$text .= self::getTableChanges($table, dbConnection::getInstance()->adapter->database, $model, false);
			} else {
				// table is not created
				$text .= self::getTableCreate($table, dbConnection::getInstance()->adapter->database, $model, false);
			}
			if ($model->extendedTextsSupport) {
				$tableExt = dbConnection::getInstance($testDatabase ? 'TestDatabase' : null)->tablePrefix() . $model->getTableExtName(false);
				if ($checkExisting && dbConnection::getInstance()->tableExists($tableExt)) {
					$text .= self::getTableChanges($tableExt, dbConnection::getInstance()->adapter->database, $model, true);
				} else {
					// table is not created
					$text .= self::getTableCreate($tableExt, dbConnection::getInstance()->adapter->database, $model, true);
				}
			}
		} else {
			$text .= "-- No table defined in the model $modelName.\n\n";
		}
		return $text;
	}	
	
	
	/**
	 * Checks the database and returns the changes.
	 * @param string $table Name of the table to check
	 * @param string $database Name of the database
	 * @param object $model Model
	 * @param bool $ext if true table_ext is checked, if false table is checked
	 * @return string SQL changes queries
	 */
	static private function getTableChanges($table, $database, &$model, $ext) {
		
		$text = '';
		$metadata = $model->getMetadata();
		$extraIndexes = array();
		if ($ext) {
			$metadata[] = AtributesFactory::create('lang')
				->setType(Form::FORM_INPUT_TEXT)
				->setExtendedTable(true)
				->setSqlType('INT(11) NOT NULL DEFAULT 1');
			$metadata[] = AtributesFactory::create('item')
				->setType(Form::FORM_INPUT_TEXT)
				->setExtendedTable(true)
				->setSqlType('INT(11) NOT NULL DEFAULT 0');
			$index = new ModelMetaIndex(array('lang', 'item'), ModelMetaIndex::PRIMARY);
			$extraIndexes[$index->name] = $index;
			/*
			foreach ($metadata as $meta) {
				if ($meta->getExtendedTable() && $meta->hasRestrictions(Restriction::R_UNIQUE)) {
					$extraIndexes[] = new ModelMetaIndex(array($meta->getName(), 'lang'), ModelMetaIndex::UNIQUE);
				}
			}*/
		} elseif ($model->languageSupportAllowed) {
			$metadata[] = AtributesFactory::create('lang')
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('INT(11) NOT NULL DEFAULT 1');
		}
		// get table columns
		$dbColumns = dbConnection::getInstance()->getColumns($table);
		$dbIndexes = dbConnection::getInstance()->getIndexesFromTable($table);
		$modelIndexes = array_merge($model->getIndexes($ext), $extraIndexes);

		$sqlItems = array();
		$sqlIndexCreate = array();
		$sqlIndexDrop = array();
		
		// Compare columns in DB and columns according model definition 
		foreach ($metadata as $meta) {
			if (in_array($meta->getType(), array(Form::FORM_MULTISELECT_FOREIGNKEY,
				Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE, Form::FORM_SPECIFIC_NOT_IN_DB))) {
				continue;
			}
			if ($model->extendedTextsSupport && ($ext && !$meta->getExtendedTable() || !$ext && $meta->getExtendedTable())) {
				continue;
			}
			if ($meta->getSqlType() === null) {
				continue;
			}
			$found = false;
			foreach ($dbColumns as $key => $column) {
				if ($column->name == $meta->getName()) {
					unset($dbColumns[$key]);
					$found = true;
					$type = preg_replace('/^\s*(\w+)\W*(.*)$/', '$1', $meta->getSqlType());
					$typeNow = preg_replace('/^\s*(\w+)\W*(.*)$/', '$1', $column->type);
					if (strcmp(strtolower($typeNow), strtolower($type)) !== 0) {
						// typy nesouhlasi
						$sqlItems[] = 'CHANGE `' . $meta->getName() . '` `' . $meta->getName() . '` ' . $meta->getSqlType() . " /* should be $type, but $typeNow is */";
					}
				}
			}
			if (!$found) {
				$sqlItems[] = 'ADD `' . $meta->getName() . '` ' . $meta->getSqlType();
			}
		}
		
		// drop columns which are not needed
		foreach ($dbColumns as $key => $column) {
				$sqlItems[] = 'DROP `' . $column->name . '`';
		}
		
		
		// Compare indexes in DB and indexes according model definition
		foreach ($modelIndexes as $modelIndex) {
			$found = false;
			foreach ($dbIndexes as $key => $dbIndex) {
				if ($dbIndex->name == $modelIndex->name) {
					unset($dbIndexes[$key]);
					$found = true;
					if ($modelIndex->columns === $dbIndex->columns) {
						// columns don't correspond
						$sqlIndexDrop[] = dbConnection::getInstance()->getIndexDropSQL($dbIndex, $table) . " /* columns should be " . implode(', ', $modelIndex->columns) . ", but currently are " . implode(', ', $dbIndex->columns) . " */";
						$found = false;
					} else if (strcmp($modelIndex->type, $dbIndex->type)) {
						// type doesn't correspond
						$sqlIndexDrop[] = dbConnection::getInstance()->getIndexDropSQL($dbIndex, $table) . " /* type should be {$modelIndex->type}, but currently is {$dbIndex->type} */";
						$found = false;
					}
				}
			}
			if (!$found) {
				$sqlIndexCreate[] = dbConnection::getInstance()->getIndexCreateSQL($modelIndex, $table, $ext);
			}
		}
		
		// drop indexes which are not needed
		foreach ($dbIndexes as $key => $dbIndex) {
			$sqlIndexDrop[] = dbConnection::getInstance()->getIndexDropSQL($dbIndex, $table) . " /* index {$dbIndex->name} is not defined in model */";
		}

		if (empty($sqlItems) && empty($sqlIndexCreate) && empty($sqlIndexDrop)) {
			$text .= '-- No changes needed in the table ' . $table . '.' . "\n\n";
		} else {
			$text .= "\n\n";
			$text .= "-- --------------------------------------------\n";
			$text .= "-- Table changes SQL for table $table.\n";
			$text .= "-- --------------------------------------------\n";
			if (!empty($sqlItems)) {
				$text .= "ALTER TABLE `$table` \n";
				$text .= implode(",\n", $sqlItems);
				$text .= ";\n";
			}
			$text .= "\n";
			if (!empty($sqlIndexDrop)) {
				$text .= implode("\n", $sqlIndexDrop);
				$text .= "\n\n";
			}
			if (!empty($sqlIndexCreate)) {
				$text .= implode("\n", $sqlIndexCreate);
				$text .= "\n";
			}
			$text .= "\n";
		}
		return $text;
	}

	
	/**
	 * Create table SQL.
	 * @param string $table Name of the table to check
	 * @param string $database Name of the database
	 * @param object $model Model
	 * @param bool $ext if true table_ext is checked, if false table is checked
	 * @return string SQL changes queries
	 */
	static private function getTableCreate($table, $database, &$model, $ext) {
		
		$text = '';
		$metadata = $model->getMetadata();
		$extraIndexes = array();
		if ($ext) {
			$metadata[] = AtributesFactory::create('lang')
				->setType(Form::FORM_INPUT_TEXT)
				->setExtendedTable(true)
				->setSqlType('INT(11) NOT NULL DEFAULT 1');
			$metadata[] = AtributesFactory::create('item')
				->setType(Form::FORM_INPUT_TEXT)
				->setExtendedTable(true)
				->setSqlType('INT(11) NOT NULL DEFAULT 0');
			$index = new ModelMetaIndex(array('lang', 'item'), ModelMetaIndex::PRIMARY);
			$extraIndexes[$index->name] = $index;
		} elseif ($model->languageSupportAllowed) {
			$metadata[] = AtributesFactory::create('lang')
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('INT(11) NOT NULL DEFAULT 1');
		}
		// get table columns
		$modelIndexes = array_merge($model->getIndexes($ext), $extraIndexes);

		$sqlItems = array();
		$sqlIndexCreate = array();
		
		// Compare columns in DB and columns according model definition 
		foreach ($metadata as $meta) {
			if (in_array($meta->getType(), array(Form::FORM_MULTISELECT_FOREIGNKEY,
				Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE, Form::FORM_SPECIFIC_NOT_IN_DB))) {
				continue;
			}
			if ($model->extendedTextsSupport && ($ext && !$meta->getExtendedTable() || !$ext && $meta->getExtendedTable())) {
				continue;
			}
			if ($meta->getSqlType() === null) {
				continue;
			}
			$sqlItems[] = '`' . $meta->getName() . '` ' . $meta->getSqlType();
		}
		
		// Compare indexes in DB and indexes according model definition
		foreach ($modelIndexes as $modelIndex) {
			// primary key is defined in column specification during creating table
			if ($modelIndex->type != ModelMetaIndex::PRIMARY)
				$sqlIndexCreate[] = dbConnection::getInstance()->getIndexCreateSQL($modelIndex, $table, $ext);
		}
		
		$engine = dbConnection::getInstance()->getEngineSQL($ext);

		if (empty($sqlItems) && empty($sqlIndexCreate) && empty($sqlIndexDrop)) {
			$text .= '-- No changes needed for the table ' . $table . '.' . "\n\n";
		} else {
			$text .= "\n\n";
			$text .= "-- --------------------------------------------\n";
			$text .= "-- Table create SQL for table $table.\n";
			$text .= "-- --------------------------------------------\n";
			if (!empty($sqlItems)) {
				$text .= "CREATE TABLE `$table` (\n";
				$text .= implode(",\n", $sqlItems);
				$text .= ") $engine ;\n";
			}
			$text .= "\n";
			if (!empty($sqlIndexCreate)) {
				$text .= implode("\n", $sqlIndexCreate);
				$text .= "\n";
			}
			$text .= "\n";
		}
		return $text;
	}
	
	
	static public function doMultipleQueries($sql, &$errors=null, $testDatabase=false) {
		return dbConnection::getInstance($testDatabase ? 'TestDatabase' : 'ProductionDatabase')->multipleQueries($sql, $errors);
	}
	
	
	static public function getCheckDbSQL() {
		$tmp = new self();
		$items = $tmp->getCollectionItems();
		$checkSQL = '';
		foreach ($items['items'] as $dmItem) {
			$checkSQL .= self::getCheckTable($dmItem->id);
		}
		return $checkSQL;
	}
	
	
	/**
	 * Method returns construct SQL of the table
	 * @param string $modelName name of the module
	 * @param null|bool $ext if true only the table_ext's constructor will be shown
	 *        if false only the table's constructor will be shown
	 *        if null, both table's and table_ext's constructor will be shown
	 * @return string construct SQL of the table
	 */
	static public function getConstructTable($modelName, $ext=null, $testDatabase=false) {
		if (!class_exists($modelName)) 
			throw new Exception("Class '$modelName' doesn't exists.");
		$model = new $modelName();
		$metadata = $model->getMetadata();
		$text = '';
		if (array_key_exists("table", get_object_vars($model)) && $model->tableBase && $model->useInInitDatabase) {	// if the model has table defined
			if ($ext === false || $ext === null) {
				// table InnoDB - keys
				$table = dbConnection::getInstance($testDatabase ? 'TestDatabase' : '')->tablePrefix() . $model->getTableName(false);
				// buffer for table InnoDB - keys
				$sqlItems = array();
				$sqlIndex = array();
				if ($model->languageSupportAllowed) {
					$sqlItems[] = '  `lang` INT(11) NOT NULL DEFAULT 0';
					$sqlIndex[] = 'KEY (`lang`)';
				}
			}
			
			if ($ext === true || $ext === null) {
				// table MyISAM - strings
				$tableExt = dbConnection::getInstance($testDatabase ? 'TestDatabase' : '')->tablePrefix() . $model->getTableExtName(false);
				// buffer for table MyISAM - strings
				$extSqlItems = array();
				$extSqlIndex = array();
				// add some special fields to join table_ext to table
				if ($model->extendedTextsSupport) {
					$extSqlItems[] = '  `item` INT(11) NOT NULL DEFAULT 0';
					$extSqlItems[] = '  `lang` INT(11) NOT NULL DEFAULT 1';
					$extSqlIndex[] = 'PRIMARY KEY (`item`,`lang`)';
				}
			}
			foreach ($metadata as $meta) {
				
				// choose which table's buffer to use
				if ($meta->getExtendedTable() && $model->extendedTextsSupport) {
					$items = &$extSqlItems;
					$index = &$extSqlIndex;
				} else {
					$items = &$sqlItems;
					$index = &$sqlIndex;
				}
				
				// add field type to buffer
				if ($meta->getSqlType()) {
					$items[] = '  `' . $meta->getName() . '` ' . $meta->getSqlType();
				}
				
				// not to add fulltext index to non-MyISAM table - not supported by InnoDB, so do not use it
				if ($meta->getSqlIndex() == 'fulltext' && !$model->extendedTextsSupport) {
					$meta->setSqlIndex('');
				}
				
				// add index to buffer
				if ($meta->getSqlIndex()) {
					switch ($meta->getSqlIndex()) {
						case ModelMetaIndex::PRIMARY: $index[] = 'PRIMARY KEY (`' . $meta->getName() . '`)'; break;
						case ModelMetaIndex::INDEX: $index[] = 'KEY (`' . $meta->getName() . '`)'; break;
						case ModelMetaIndex::UNIQUE:
							$keyLength = ((stripos($meta->getSqlType(), 'text') !== false) ? ' (255)' : '');
							if ($meta->getExtendedTable() && $model->extendedTextsSupport) {
								// lang column should be added to index
								// TODO: this added to checking to
								$index[] = 'UNIQUE KEY (`' . $meta->getName() . '`' . $keyLength . ', `lang`)'; break;
							} else {
								// simple one column unique index
								$index[] = 'UNIQUE KEY (`' . $meta->getName() . '`' . $keyLength . ')'; break;
							}
						case ModelMetaIndex::FULLTEXT: $index[] = 'FULLTEXT KEY (`' . $meta->getName() . '`)'; break;
						default: break;
					}
				}
			}
			
			// multi-column indexes
			foreach ($model->indexes as $metaIndexesType => $metaIndexes) {
				// loop the metaData indexes
				foreach ($metaIndexes as $metaIdexName => $metaIndexColumns) {
					$constrColumns = array();
					foreach ($metaIndexColumns as $mc) {
						$mcexpl = explode("|", $mc);
						if (count($mcexpl) > 1) {
							$constrColumns[] = '`' . $mcexpl[0] . '` (' . $mcexpl[1] . ')';
						} else {
							$constrColumns[] = '`' . $mc . '`';
						} 
					}
					$constrColumns = implode(', ', $constrColumns);
					switch ($metaIndexesType) {
						default:
						case ModelMetaIndex::INDEX: $metaIndexesType = "KEY"; break;
						case ModelMetaIndex::UNIQUE: $metaIndexesType = "UNIQUE KEY"; break;
					}
					$sqlIndex[] = $metaIndexesType . " `$metaIdexName` ($constrColumns)";
				}
			}
			
			// generate table
			if ($ext === false || $ext === null) {
				if (empty($sqlItems)) {
					$text .= '-- There are no columns defined in the model ' . $modelName . '.';
				} else {
					$text .= "\n\n";
					$text .= "-- --------------------------------------------\n";
					$text .= "-- Table create SQL for model $modelName.\n";
					$text .= "-- --------------------------------------------\n";
					$text .= "CREATE TABLE IF NOT EXISTS `$table` (\n";
					$text .= implode(",\n", $sqlItems);
					if (count($sqlIndex)) {
						$text .= ",\n";
						$text .= implode(",\n", $sqlIndex);
					}
					$text .= "\n)";
					if (dbConnection::getInstance()->adapter->dbtype == 'mysql') $text .= " ENGINE=InnoDB";
					$text .= ";\n\n";
				}
			}

			if ($ext === true || $ext === null) {
				// generate table_ext
				if (empty($extSqlItems)) {
					$text .= '-- No columns defined in the extending table in the model ' . $modelName . '.';
				} else {
					$text .= "-- Extending table create SQL for model $modelName.\n";
					$text .= "CREATE TABLE IF NOT EXISTS `$tableExt` (\n";
					$text .= implode(",\n", $extSqlItems);
					if (count($extSqlIndex)) {
						$text .= ",\n";
						$text .= implode(",\n", $extSqlIndex);
					}
					$text .= "\n)";
					if (dbConnection::getInstance()->adapter->dbtype == 'mysql') $text .= " ENGINE=MyISAM";
					$text .= ";\n\n";
				}
			}
		} else {
			$text .= "-- --------------------------------------------\n";
			$text .= "-- No table defined in the model $modelName.\n";
			$text .= "-- --------------------------------------------\n";
		}
		return $text;
	}


	static public function getConstructDBInstall() {
		$constructSQL = self::getConstructSQL();
		
		$um = new BaseUsersModel();
		$lm = new BaseLanguagesModel();
		$dm = new BaseDictionaryModel();
		
		$randomPassword = Utilities::generatePassword();
		$constructSQL .= "-- --------------------------------------------\n";
		$constructSQL .= "-- Inserting of the admin user.\n";
		$constructSQL .= "-- login: admin@server.com\n";
		$constructSQL .= "-- password: $randomPassword\n";
		$constructSQL .= "-- --------------------------------------------\n";
		$defaultEmails = preg_split('/[,;]/', Config::Get('DEFAULT_EMAIL'));
		$defaultEmail = $defaultEmails[0];
		$constructSQL .= "insert into " . $um->getTableName() . " (email, password, permissions, active) values ('$defaultEmail', md5('$randomPassword'), 1, 1);\n";
		// insert languages for frontend and backend (that are values in $frontActive and $backActive 
		$frontActive = $backActive = '1, 1';
		if (Config::Get('DEFAULT_LANGUAGE') != Config::Get('DEFAULT_LANGUAGE_BACK_END')) {
			$backActive = '0, 1';
			$frontActive = '1, 0';
			$constructSQL .= "insert into " . $lm->getTableName() . " (title, url, front_end, back_end) values ('" . Language::$languageNames[Config::Get('DEFAULT_LANGUAGE_BACK_END')] . "', '" . Config::Get('DEFAULT_LANGUAGE_BACK_END') . "', $backActive);\n";
		}
		$constructSQL .= "insert into " . $lm->getTableName() . " (title, url, front_end, back_end) values ('" . Language::$languageNames[Config::Get('DEFAULT_LANGUAGE')] . "', '" . Config::Get('DEFAULT_LANGUAGE') . "', $frontActive);\n";
		$constructSQL .= "insert into " . $dm->getTableName() . " (`key`, `language`, `text`, `kind`, `automatic`) values ('Project Title', 1, '" . Config::Get('PROJECT_TITLE') . "', 1, 1);\n";
		$constructSQL .= "insert into " . $dm->getTableName() . " (`key`, `language`, `text`, `kind`, `automatic`) values ('Project Description', 1, '" . Config::Get('PROJECT_DESCRIPTION') . "', 1, 1);\n";
		
		return $constructSQL;
	}
	
	
	static public function getConstructDbInit() {
		$constructSQL = self::getConstructSQL();
		
		$um = new BaseUsersModel();
		$lm = new BaseLanguagesModel();
		
		$randomPassword = Utilities::generatePassword();
		$constructSQL .= "-- --------------------------------------------\n";
		$constructSQL .= "-- Inserting of the admin user.\n";
		$constructSQL .= "-- login: admin@server.com\n";
		$constructSQL .= "-- password: $randomPassword\n";
		$constructSQL .= "-- --------------------------------------------\n";
		$constructSQL .= "insert into " . $um->getTableName() . " (email, password, permissions, active) values ('admin@server.com', md5('$randomPassword'), 1, 1);\n";
		$constructSQL .= "insert into " . $lm->getTableName() . " (title, url, front_end, back_end) values ('English', 'en', 1, 1);\n";
		
		return $constructSQL;
	}
	
	public function Find($modelName, $filters, $values) {
		$modelName = $values[0];
		if (class_exists($modelName)) {
			$item = new BaseDatabaseTableModel($modelName);
			$item->id = $modelName;
			return array($item);
		}
		return false;
	}

}

?>
