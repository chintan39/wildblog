<?php

class AbstractBasicController {
	
	var $model;
	var $package;
	var $description = '';
	public $order = 5;				// order of the controller (0-10)
	
	/**
	 * Constructor
	 * Bind controller with the Data model
	 * Set the template engine reference to the attribute to confortable accesss
	 * @param string $model Data model name
	 * @param string $package Package name
	 */
	public function __construct($model, $package) {
		
		$this->name = preg_replace('/^' . $package . '(.*)Controller$/', '$1', get_class($this));
		$this->description = $this->name . ' description';
		$this->package = $package;
		if (class_exists($model)) {
			$this->model = $model;
		}
	}
	
	
	public function getName() {
		return $this->name;
	}
	
	public function getModel() {
		return $this->model;
	}
	
	public function getPackage() {
		return $this->package;
	}
	
	public function getPackageObject() {
		return Environment::getPackage($this->package);
	}
	
	public function assign($variable, $value) {
		Environment::$smarty->assign($variable, $value);
	}
	

	/**
	 * Change items in the admin menu.
	 */
	public function getLinksAdminMenuLeft() {
		return array();
	}
	
	/** 
	 * Searching method returns all items that should be found.
	 * @return array of object
	 */
	public function getSearchItems($text) {
		return array();
	}
}

?>