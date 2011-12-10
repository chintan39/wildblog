<?php

/**
 * This class represents qualifications of items.
 */
class ItemQualification {
	var $filter;
	var $value;
	
	public function __construct($filter, $value) {
		$this->filter = $filter;
		$this->value = $value;
	}
}

/**
 * This class represents sorting of item collections.
 */
class ItemSorting {
	var $column;
	var $direction;
	
	public function __construct($column, $direction=SORTING_ASC) {
		$this->column = $column;
		$this->direction = $direction;
	}
}

?>
