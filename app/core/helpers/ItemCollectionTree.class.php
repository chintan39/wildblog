<?php

/**
 * This class represents the collections of some items (data items). 
 * The collection could be another layer between controller and model, 
 * which basicly belongs to the controller, but it can handle some 
 * controller-independent operations.
 */
class ItemCollectionTree extends ItemCollection {
	
	const treeRoot = 1;
	
	const treeAncestors = 1;
	const treeDescendants = 2;
	const treeSiblings = 4;
	const treeIncludeRoot = 8;
	const treeAll = 15;

	public
	$dataModelMethod='getCollectionItemsTree';
	
	private 
	$treeBase=self::treeRoot,
	$treePull=self::treeAll,
	$treeLevel=10;
	
	
   	
   	
	/**
	 * Sets base item or root
	 * @param <object> $baseItem
	 */
	public function treeBase($baseItem) {
		$this->treeBase = $baseItem;
	}
	

	/**
	 * Sets what items to pull in tree
	 * @param <int> $pullSet
	 */
	public function treePull($pullSet) {
		$this->treePull = $pullSet;
	}
	

	/**
	 * Sets how many levels of items to pull into tree
	 * @param <int> $pullLevel
	 */
	public function treeLevel($pullLevel) {
		$this->treeLevel = $pullLevel;
	}
	
	
	protected function passPropertiesToDm() {
		parent::passPropertiesToDm();
		$this->getDm()->setTreeSpec($this->treeBase, $this->treePull, $this->treeLevel);
	}
	
}

?>
