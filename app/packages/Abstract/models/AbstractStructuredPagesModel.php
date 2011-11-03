<?php

class AbstractStructuredPagesModel extends AbstractPagesModel {
	
	var $package='Abstract';

	const ALLOWED_RECURSIVE_LEVEL = 10;

    protected function attributesDefinition() {

		parent::attributesDefinition();
		
		$this->addMetaData(AbstractAttributesModel::stdParent());
	}
	

    protected function relationsDefinition() {

    	parent::relationsDefinition();

        $this->addCustomRelation($this->name, 'parent', 'id'); // define a 1:many relation to Reaction 
    }
    

    /**
     * Returns the list of items to make the relation to another model. 
     * So the items returned will be used by the select list.
     * @return array List of items
     */
    public function listSelectTree($itemIdArray=null) {
   		$tmpCollection = new ItemCollection("listSelectTreeCollection", null, get_class($this), "getCollectionItemsTreeNoPaging");
   		$tmpCollection->setTreeHigh(5);
		$tmpCollection->loadCollection();
    	return $tmpCollection->toSimpleSelectTree();
    }
    
	/**
	 *
	 * @param 
	 */
	public function getCollectionItemsTree($itemCollectionIdentifier, $parentIdArray=array(), $pagingLimit=DEFAULT_PAGING_LIMIT) {
    	if (count($parentIdArray)) {
    		$filters = array(" parent in (?" . str_repeat(", ?", count($parentIdArray)-1) . ")");
			$values = $parentIdArray;
    	} else {
			$filters = array("parent = ?");
			$values = array(0);
    	}
    	return $this->getCollectionItems(
    		$itemCollectionIdentifier, 
    		$this,	// model
    		$filters, // filters
    		$values, // values
    		array(), // extras
    		array(), // just these
    		array(), // order
    		$pagingLimit	// limit
    	);
	}

	/**
	 *
	 * @param 
	 */
	public function getCollectionItemsTreeNoPaging($itemCollectionIdentifier, &$parentIdArray=array()) {
		return $this->getCollectionItemsTree($itemCollectionIdentifier, $parentIdArray, 0);
	}

	
	protected function checkFieldValue(&$meta, &$newData) {
		// check all basic fields
		parent::checkFieldValue($meta, $newData);
		
		// check parent field for recursive cyclus - that is dangerous
		if ($meta->getType() == Form::FORM_SELECT_FOREIGNKEY && array_key_exists($this->name, $this->relations) && $this->relations[$this->name]->sourceProperty == $meta->getName()) {
			$this->checkRecursiveCyclus($meta, $newData);
		}
	}
	
	/**
	 *
	 * @param 
	 */
	private function checkRecursiveCyclus(&$meta, &$newData) {
		if ($this->id) {
			$usedItemsId = array($this->id);
			$this->checkRecursiveCyclusLevel($meta, 0, $usedItemsId, $this->parent);
		}
	}
	
	/**
	 *
	 * @param 
	 */
	private function checkRecursiveCyclusLevel(&$meta, $level, &$usedItemsId, $currentId) {
		if ($level >= self::ALLOWED_RECURSIVE_LEVEL) {
			$this->addMessageField("errors", $meta, "is not filled correctly. Recursively cyclus is too deep, items are maybe recursively depended, check the dependence in the tree"); 
			return;
		}
		$model = $this->name;
		$item = new $model($currentId);
		if (!$item) {
			$this->addMessageField("errors", $meta, "is not filled correctly. Item is depended on non-existing item");
			return;
		}
		if (in_array($item->id, $usedItemsId)) {
			$this->addMessageField("errors", $meta, $item->id."is not filled correctly. Items are recursively depended, check the dependence in the tree"); 
			return;
		}
		$usedItemsId[] = $item->id;
		if ($item->parent != 0) {
			$this->checkRecursiveCyclusLevel($meta, $level+1, $usedItemsId, $item->parent);
		}
	}
	
}

?>
