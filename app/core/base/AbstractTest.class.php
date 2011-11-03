<?php

class AbstractTest {
	
	var $package;
	var $name;
	var $description;
	
	public function __construct() {
		$this->name = get_class($this);
		//$this->name = preg_replace('/^' . $package . '(.*)Test$/', '$1', get_class($this));
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}
	
	public function run() {}
}

?>