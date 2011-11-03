<?php

class BasicMenuItemsModel extends AbstractCodebookModel {
	
	var $package = 'Basic';
	var $icon = 'page', $table = 'menu_items';
	var $languageSupportAllowed = true;
	var $activity;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AbstractAttributesModel::stdParent());
		
		$this->addMetaData(AbstractAttributesModel::stdLink());
		
		$this->addMetaData(ModelMetaItem::create('menu')
			->setLabel('Menu')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation($this->name, 'parent', 'id'); // define a 1:many relation to Reaction 
	    $this->addCustomRelation('BasicMenuModel', 'menu', 'id'); // define a 1:many relation to Reaction 
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
	public function getCollectionItemsTreeNoPaging($itemCollectionIdentifier, &$parentIdArray=array()) {
		return $this->getCollectionItemsTree($itemCollectionIdentifier, $parentIdArray, 0);
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


} 

?>