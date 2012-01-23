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
