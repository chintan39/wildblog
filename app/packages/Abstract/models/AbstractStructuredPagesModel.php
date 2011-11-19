<?php

class AbstractStructuredPagesModel extends AbstractPagesModel {
	
	var $package='Abstract';

	const ALLOWED_RECURSIVE_LEVEL = 10;

	private 
	$treeBase,
	$treePull,
	$treeLevel;
	
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
    public function listSelectTree() {
    	$this->treeBase = ItemCollectionTree::treeRoot;
    	$this->treePull = ItemCollectionTree::treeDescendants;
    	$this->treeLevel = 10;
		$output = array();
   		$output[] = array('id' => 0, 'value' => 'Top');
   		$totalCount=0;
   		$items = $this->getItemsTree($totalCount);
		$this->toSimpleSelectTreeLevel($items, $output, 0, false);
    	return $output;
    }


	/**
	 * Returns intent of the lines in tree in select
	 * @param int $indent
	 * @return string
	 */
	public function getIndent($indent) {
		return str_repeat('&nbsp;', 4 * $indent) . (($indent > 0) ? '!-' : '');
	}
	

	/**
	 * Makes a list prepared into the select tag and displaying as tree.
	 * Works recursively.
	 */
	public function toSimpleSelectTreeLevel(&$items, &$output, $indent, $disabled) {
		$requestAction = Request::getRequestAction();
		$indentStr = $this->getIndent($indent);
		if ($items) {
			foreach ($items as $i) {
				$newItem = array('id' => $i->id, 'value' => $indentStr . $i->makeSelectTitle());
				$newDisabled = ($disabled || $requestAction['item'] && get_class($requestAction['item']) == get_class($i) && $requestAction['item']->id == $i->id);
				if ($newDisabled) {
					$newItem['disabled'] = true;
				}
				$output[] = $newItem;
				if ($i->subItems) {
					$this->toSimpleSelectTreeLevel($i->subItems, $output, $indent+1, $newDisabled);
				}
			}
		}
   	}	

   	
   	protected function getItemsTree(&$totalCount) {
		$actualLevel = $this->treeLevel;
		$totalCount = 0;
		
		// get starting items
		if ($this->treeBase === ItemCollectionTree::treeRoot) {
			$this->clearQualification('parent');
			$this->addQualification(" parent  = ? ", array(0), 'parent');
			$resultItems = $this->getItems();
		} else {
			if ($this->treePull & ItemCollectionTree::treeSiblings) {
				$this->clearQualification('parent');
				$this->addQualification(" parent  = ? ", array($this->treeBase->parent), 'parent');
				$resultItems = $this->getItems();
				$actualLevel++;
			} else {
				$resultItems = array($this->treeBase);
			}
		}
		
		$returnItems = $resultItems;
		
		// pulling descendants of all found items
		while (count($resultItems)) {
			$resultItemsCount = count($resultItems);
			$totalCount += $resultItemsCount;
		
			// what parents will we find?
			$parentIdArray = array();
			foreach ($resultItems as $item) {
				$parentIdArray[] = $item->id;
				$item->addNonDbProperty('subItems'); 
				$item->subItems = array();
			}
			
			if (!$actualLevel--) {
				break;
			}

			// find the parents
			$this->clearQualification('parent');
			$this->addQualification(" parent in (?" . str_repeat(", ?", count($parentIdArray)-1) . ")", $parentIdArray, 'parent');
			$tmpItems = $this->getItems();
			
			// when nothing new found, we're done
			if (!$tmpItems)
				break;
			
			// loop through new items and find what parent to assign them to
			foreach ($tmpItems as $item) {
				$actualParent = 0;
				while ($item->parent != $resultItems[$actualParent]->id && $actualParent<$resultItemsCount)
					$actualParent++;
				
				// assign the item to the right parent
				if ($item->parent == $resultItems[$actualParent]->id)
					$resultItems[$actualParent]->subItems[] = $item;
			}
			$resultItems = $tmpItems;
		}

		// if we want ancestors, we go from base above
		if ($this->treePull & ItemCollectionTree::treeAncestors && $this->treeBase !== ItemCollectionTree::treeRoot) {
			$tmpId = $this->treeBase->parent;
			while ($tmpId > 0) {
				$totalCount++;
				$tmp = new self($tmpId);
				$tmp->addNonDbProperty('subItems'); 
				$tmp->subItems = $resultItems;
				$returnItems = array($tmp);
				$tmpId = $tmp->parent;
			}
		}
		return $returnItems;
   	}
   	
    
	/**
	 *
	 * @param 
	 */
	public function getCollectionItemsTree() {

		$list = array();
		$totalCount = '';
		$list["items"] = $this->getItemsTree($totalCount);
		$list["columns"] = $this->getVisibleColumnsInCollection();
		$list["itemsCount"] = $totalCount;
		return $list;
	}

	
	/**
	 * Deprecated.
	 */
	protected function getSubItems($parentId) {
		$result = array();
		if ($parentId <= 0)
			return $result;
		$this->clearQualification('parent');
		$this->addQualification("parent = ?", array($parentId), 'parent');
		$tmp = $this->getItems();
		if ($tmp) {
			foreach ($tmp as $item) {
				$item->addNonDbProperty('subItems'); 
				$item->subItems = $this->getSubItems($item->parent);
				$result[] = $item;
			}
		}
		return $result;
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
	
	public function setTreeSpec($root, $pull, $level) {
		$this->treeBase = $root;
		$this->treePull = $pull;
		$this->treeLevel = $level;
	}
	
}

?>
