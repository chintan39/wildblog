<?php

require_once('AbstractAttributesModel.php');

class AbstractDefaultModel extends AbstractDBObjectModel {
	
	var $package="Abstract";
	var $icon="page";
	var $useInInitDatabase = true;
	protected $qualification=array();		/*	basic definition of the active items (active = 1 for example) */
	protected $sorting=array();			/*	basic definition of the sorting items ('column'=>'rank', 'direction'=>'desc' for example) */
	protected $limit=array();				/*	basic definition of the limit of items (5 for example) */
	
    function __construct($id = false, $forceLanguage = false) {
		$this->id = $id;
    	parent::__construct($id);
    	if ($forceLanguage) {
    		$this->forceLanguage($forceLanguage);
    	}
    	$this->fields = $this->getFieldsInDB();
		$this->tableBase = $this->getTableName();
		$this->primary = $this->getPrimaryKey();
		$this->connection = 'ProductionDatabase';
    	$this->__setupDatabase();
		$this->relationsDefinition();
		$this->qualificationDefinition();
		$this->sortingDefinition();
		$this->limitDefinition();
    }
    
    public function getTableName($prefix=true) {
    	return ($prefix ? dbConnection::getInstance()->tablePrefix() : '') . strtolower($this->package) . "_" . $this->table;
    }
    
    public function getTableExtName($prefix=true) {
    	return ($prefix ? dbConnection::getInstance()->tablePrefix() : '') . strtolower($this->package) . "_" . $this->table . "_ext";
    }
    
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdId());
    }
    
	/**
	 * Returns Primary Key Field for setup DB
	 * @return string Primary Key Field for setup DB
	 */
    private function getPrimaryKey() {
    	$primary = false;
    	foreach ($this->getMetaData() as $field => $meta) {
    		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_PRIMARY)) {
    			return $field;
    		}
    	}
    	throw new Exception("Model " . $this->name . " has no primary key attribute.");
    }
    
	/**
	 * Relations to other models definition
	 * Must be overwritten.
	 * Changes private attribute metaData.
	 */
    protected function relationsDefinition() {
    }
    
	/**
	 *
	 * @return
	 */
	protected function getColumns() {
		$columns = array();
		foreach ($this->fields as $column) {
			$columns[] = $column;
		}
		return $columns;
	}
	
	/**
	 *
	 * @param $itemCollectionIdentifier
	 * @param $modelName
	 * @param $filters
	 * @param $values
	 * @param $extra
	 * @param $justThese
	 * @param $order
	 * @param $limit
	 *
	 * @return
	 */
	public function getCollectionItems() {
		$list = array();
		$list["items"] = $this->getItems();
		$list["columns"] = $this->getVisibleColumnsInCollection();
		$list["itemsCount"] = $this->getItemsCount();
		return $list;
	}
	
	
	/**
	 *
	 * @param $modelName
	 * @param $filters
	 * @param $values
	 * @param $extra
	 * @param $justThese
	 *
	 * @return
	 */
	public function getItems($modelName=false, $filters=array(), $values=array(), $extra=array(), $justThese=array()) {
		if (!$modelName) {
			$modelName = get_class($this);
		}
		$this->exportQualifications($filters, $values);
		$this->exportSorting($extra);
		$this->exportLimit($extra);
		return $this->Find($modelName, $filters, $values, $extra, $justThese);
	}
	
	/**
	 *
	 * @param $modelName
	 * @param $filters
	 * @param $values
	 * @param $extra
	 * @param $justThese
	 *
	 * @return
	 */
	protected function getItemsCount($modelName=false, $filters=array(), $values=array(), $extra=array(), $justThese=array()) {
		if (!$modelName) {
			$modelName = get_class($this);
		}
		$this->exportQualifications($filters, $values);
		return $this->findCount($modelName, $filters, $values, $extra, $justThese);
	}
	
	protected function getOrderSQL($order) {
		if (count($order)) {
			$oSql = array();
			foreach ($order as $o) {
				$oSql[] = "`" . $o["column"] . "` " . $o["direction"];
			}
			return " ORDER BY " . implode(", ", $oSql);
		}
		return "";
	}

	/**
	 * Checks field's uniquity (for example email in registration).
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldUnique(&$value, &$meta) {
		if ($this->fieldIsNotUnique($value, $meta)) {
			$this->addMessageField("errors", $meta, "must be unique"); 
		}
	}
	
	protected function fieldIsNotUnique(&$value, &$meta) {
		if ($this->id) {
			return ($this->findCount($this->name, array($meta->getName() . " = ?", "id != ?"), array($value, $this->id)) > 0);
		} else {
			return ($this->findCount($this->name, array($meta->getName() . " = ?"), array($value)) > 0);
		}
	}
	
	/**
	 *
	 * @return
	 */
	public function getFields() {
		return $this->fields;
	}
	
	/**
	 * Analyzes the relations sequentialy and returns name of the model, 
	 * which correspods with the field (which is the foreight key).
	 * @param string $fieldName Name of the field, which should be analyzed.
	 * @return string Name of the Model is returned.
	 */
	public function getRelationModel($fieldName) {
		foreach ($this->relations as $modelName => $relationInfo) {
			if ($relationInfo->sourceProperty == $fieldName) {
				return $modelName;
			}
		}
		return false;
	}

    /**
     * Returns the list of items to make the relation to another model. 
     * So the items returned will be used by the select list.
     * @return array List of items
     */
    public function listSelect() {
    	$items = $this->Find(get_class($this));
    	$selectItems = array();
    	if (is_array($items)) {
			foreach ($items as $i) {
				$selectItems[] = array("id" => $i->id, "value" => $i->makeSelectTitle());
			}
    	}
    	return $selectItems; 
    }
    
    
    /**
     * Method creates the title used in the select box.
     * Should be overwritten.
     * @return string title of the item to use in the select box
     */
    public function makeSelectTitle() {
    	if (array_key_exists('title', $this->getMetaData())) {
    		return $this->title; // TODO: make better
    	}
    	return sprintf("%s object [%d]", get_class($this), $this->id);
    }
    

	/**
	 * Returns array of fields, that should be visible in the specified collection.
	 * Examples of using visible property:
	 * 		!isset([visible])
	 * 		[visible][all] = true
	 * 		[visible][main] = false
	 * 		[visible][specialCollection] = true
	 * @param string $collectionIdentifier ID of the collection.
	 * @return array Visible fields.
	 */
	public function getVisibleColumnsInCollection() {
		$visibleColumns = array();
		$notVisibleTypes = 	array(Form::FORM_SELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE);

    	foreach ($this->getMetaData() as $field => $meta) {
    		$visible = $meta->getIsVisible();
    		if (in_array($meta->getType(), $notVisibleTypes)) {
    			$visible = false;
    		}
    		if ($visible) {
    			$visibleColumns[] = $field;
    		}
    	}
    	return $visibleColumns;
	}
	
	
	protected function qualificationDefinition() {
	}
	
	
    /**
     * Exports all qualifications into the $filters and $values.
     * @param &$filters sql query filters
     * @param &$values sql query values
     */
	protected function exportQualifications(&$filters, &$values) {
		if (!is_array($this->qualification))
			return;
		foreach ($this->qualification as $qGroup) {
			foreach ($qGroup as $q) {
				$filters[] = '(' . $q->filter . ')';
				if (is_array($q->value)) {
					// id values is array
					foreach ($q->value as $i) {
						$values[] = $i;
					}
				} else {
					// values is not array
					$values[] = $q->value;
				}
			}
		}
	}
	
	protected function sortingDefinition() {
		/* 
		 * example:
		 * $this->sorting = array(array('column' => 'published', 'direction' => 'desc'));
		 */
	}

    /**
     * Exports sort part of the SQL $extra
     * @param &$extra sql query extra
     */
	protected function exportSorting(&$extra) {
		$tmp = array();
		foreach ($this->sorting as $s) {
			if ($s->column == 'RAND') {
				$tmp[] = 'RAND()';
			} else {
				$tmp[] = $s->column . ' ' . $s->direction;
			}
		}
		if ($tmp) {
			$extra[] = "ORDER BY " . implode(", ", $tmp);
		}
	}

	
	/**
	 * Returns sorting definition. 
	 * If temporary sorting is set, it is returned, else general 
	 * sorting definition is returned.
	 */
	public function getSorting() {
		return $this->sorting;
	}
	
	
	public function getSortable() {
		$sort = $this->getSorting();
		foreach ($sort as $s) {
			if ($s->column == 'rank') {
				return $s->direction;
			}
		}
		return null;
	}
	

	protected function limitDefinition() {
	}
	
	
    public function getLimit() {
		return $this->limit;
	}
	
    public function getQualifications() {
		return $this->qualifications;
	}
	
    /**
     * Exports limit to the $extra
     * @param &$extra sql query extra
     */
    protected function exportLimit(&$extra) {
		if (array_key_exists('limit', $this->limit) && $this->limit['limit']) {
			$extra[] = "LIMIT " . $this->limit['start']. ", " . $this->limit['limit'];
		}
	}
	
	
	protected function getCacheFile($cacheIdentification) {
		return DIR_MODELS_CACHE . $this->package . '__' . $this->name . '__' . $cacheIdentification . ".inc";
	}
	
	
	protected function getCacheAffectedFile() {
		return DIR_MODELS_CACHE . $this->package . '__' . $this->name . '_affected.inc';
	}
	
	
	protected function getAllCacheFiles() {
		/*$result = array();
		foreach (scandir(DIR_MODELS_CACHE) as $file) {
			if (is_file(DIR_MODELS_CACHE . $file) && preg_match('/^' . $this->name . '__/', $file)) {
				$result[] = DIR_MODELS_CACHE . $file;
			}
		}
		return $result;
		*/
		if (file_exists($this->getCacheAffectedFile())) {
			return array_keys(unserialize(file_get_contents($this->getCacheAffectedFile())));
		} else {
			return array();
		}
	}
	
	
	protected function removeAllCacheFiles() {
		foreach ($this->getAllCacheFiles() as $file) {
			if (file_exists($file)) {
				unlink($file);
			}
		}
	}
	
	
	protected function loadCache($cacheIdentification) {
		if (Config::Get('ALLOW_CACHE') && file_exists($this->getCacheFile($cacheIdentification))) {
			return unserialize(file_get_contents($this->getCacheFile($cacheIdentification)));
		} else {
			return false;
		}
	}
	
	
	protected function saveCache($cacheIdentification, $data, $modelsAffected = array()) {
		if (!Config::Get('ALLOW_CACHE')) {
			return;
		} 
		file_put_contents($this->getCacheFile($cacheIdentification), serialize($data));
		foreach ($modelsAffected as $m) {
			$mo = new $m();
			$mo->cacheAddAffected($this->getCacheFile($cacheIdentification));
		}
		chmod($this->getCacheFile($cacheIdentification), 0600);
	}
	
	
	protected function invalidateCache() {
		$this->removeAllCacheFiles();
	} 
	
	
	public function cacheAddAffected($file) {
		if (file_exists($this->getCacheAffectedFile())) {
			$files = unserialize(file_get_contents($this->getCacheAffectedFile()));
		} else {
			$files = array();
		}
		$files[$file] = 1;
		file_put_contents($this->getCacheAffectedFile(), serialize($files));
	}

	
	public function removeNeedlessParts() {
		return $this;
	}


	/**
	 *
	 */
	public function getMetaOptions($fieldName) {
		return  MetaDataContainer::getFieldOptions($this->name, $fieldName);
	}

	/**
	 * 
	 */
	public function addQualification($filter, $value, $ident=false) {
		if ($ident !== false) {
			if (empty($this->qualification[$ident]))
				$this->qualification[$ident] = array();
			$this->qualification[$ident][] = new ItemQualification($filter, $value);
		} else 
			$this->qualification[] = array(new ItemQualification($filter, $value));
	}

	/**
	 * 
	 */
	public function setQualification($qualifications) {
		$this->qualification = $qualifications;
	}

	/**
	 * 
	 */
	public function addSorting($column, $direction=SORTING_ASC) {
		$this->sorting[] = new ItemSorting($column, $direction);
	}

	/**
	 * 
	 */
	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}

	/**
	 * 
	 */
	public function addLimit($count, $start) {
		$this->limit = array('start' => $start, 'limit' => $count);
	}

	/**
	 * 
	 */
	public function setLimit($limit) {
		$this->limit = $limit;
	}
}

?>