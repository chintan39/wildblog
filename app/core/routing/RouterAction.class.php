<?php

/**
 * Rule object.
 */

class RouterAction {
	
	public $package;
	public $routesObject;
	public $action;
	public $rules = array();
	public $order = 0; 
	public $permission;
	public $branch;
	public $template = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->branch = Themes::FRONT_END;
		$this->permission = Permission::$ALL;
	}
	
	public function setPackage($value) {
		$this->package = $value;
		return $this;
	}
	
	public function setRoutesObject($value) {
		$this->routesObject = $value;
		return $this;
	}
		
	public function setAction($value) {
		$this->action = $value;
		return $this;
	}
		
	public function setRules($value) {
		$this->rules = $value;
		return $this;
	}
		
	public function addRule($value) {
		$this->rules[] = $value;
		return $this;
	}
		
	public function addRuleUrl($value) {
		if (Request::checkHomepageAction($this->package, $this->routesObject->getName(), $this->action)) {
			$value = '$';
		}
		$urlSplited = explode("/", $value);
		for ($index = 0; $index < count($urlSplited); $index++) {
			if (!trim($urlSplited[$index])) continue;
			$routeRule = new RouterRule();
			$routeRule->addRule(RouterRule::RULE_PATH, $index);
			$routeRule->addValueSmartly($urlSplited[$index]);
			$this->rules[] = $routeRule;
		}
		return $this;
	}
		
	public function addRuleGet($getParams) {
		foreach ($getParams as $key => $value) {
			$routeRule = new RouterRule();
			$routeRule->addRule(RouterRule::RULE_GET, $key);
			$routeRule->addValueSmartly($value);
			$this->rules[] = $routeRule;
		}
		return $this;
	}
		
	public function addRulePost($postParams) {
		foreach ($postParams as $key => $value) {
			$routeRule = new RouterRule();
			$routeRule->addRule(RouterRule::RULE_POST, $key);
			$routeRule->addValueSmartly($value);
			$this->rules[] = $routeRule;
		}
		return $this;
	}
		
	public function setOrder($value) {
		$this->order = $value;
		return $this;
	}
		
	public function setPermission($value) {
		$this->permission = $value;
		return $this;
	}
		
	public function setBranch($value) {
		$this->branch = $value;
		return $this;
	}
		
	public function setTemplate($value, $package=null) {
		$this->template = $value;
		if ($package !== null) {
			$this->template = $package . '|' . $this->template;
		}
		return $this;
	}
		
	public function getPackage($value) {
		return $this->package;
	}
	
	public function getRoutesObject($value) {
		return $this->routesObject;
	}
		
	public function getAction($value) {
		return $this->action;
	}
		
	public function getRules($value) {
		return $this->rules;
	}
		
	public function getOrder($value) {
		return $this->order;
	}
		
	public function getPermission($value) {
		return $this->permission;
	}
		
	public function getBranch($value) {
		return $this->branch;
	}
		
	public function getTemplate($value) {
		return $this->template;
	}
		

}

?>
