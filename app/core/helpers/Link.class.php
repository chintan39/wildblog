<?php

/**
 * class Link
 * Link object to represent link
 */
class Link {
	
	const PASSIVE = "passive";
	const ACTIVE = "active";
	const SUPERIOR_ACTIVE = "sup_active";
	const SUPERIOR_PASSIVE = "sup_passive";
	
	var $subLinks=array(), $link, $linkRel, $autolink, $label, $title, $image, $action, $styleClass, $activity, $superiorActiveActions, $order=5;
	
	/**
	 * Constructor.
	 * TODO: remake dependency to subLinkCollection
	 */
	public function __construct($initArray=array()) {
		$this->link = isset($initArray['link']) ? $initArray['link'] : '';
		$this->linkRel = isset($initArray['link']) ? str_replace(Request::$url['base'], '', $initArray['link']) : '';
		$this->label = isset($initArray['label']) ? $initArray['label'] : null;
		$this->title = isset($initArray['title']) ? $initArray['title'] : null;
		$this->subLinks = isset($initArray['subLinks']) ? $initArray['subLinks'] : array();
		$this->image = isset($initArray['image']) ? $initArray['image'] : null;
		$this->action = isset($initArray['action']) ? $initArray['action'] : array();
		$this->styleClass = isset($initArray['styleClass']) ? $initArray['styleClass'] : null;
		$this->order = isset($initArray['order']) ? $initArray['order'] : 5;
		$this->superiorActiveActions = array();
		$this->activity = '';

		$this->autolink = isset($initArray['action']) ? $this->makeAutoLink() : '';
	}

	
	/**
	 * Add sublinks (links depended on this link)
	 */
	public function addSubLinks($links) {
		$this->subLinks = array_merge($this->subLinks, $links);
	}
	

	/**
	 * Returns sublinks (links depended on this link)
	 */
	public function getSubLinks() {
		return $this->subLinks;
	}
	

	/**
	 * Sets the activity of the link
	 */
	public function setActivity($activity) {
		$this->activity = $activity;
	}
	

	/**
	 * Sets the order of the link
	 */
	public function setOrder($order) {
		$this->order = $order;
	}
	

	/**
	 * Returns the activity of the form
	 */
	public function getActivity() {
		return $this->activity;
	}
	
	/**
	 * We can add actions (defined by controller and method), when the link should be 
	 * marked as SUPERIOR_ACTIVE. 
	 * For example: We want to mark this link, if we edit some item - but we can not 
	 * add links to all items as a subLinks.
	 * Using this method we can add some action to a list and if one action from the list is active, 
	 * this link will be marked as SUPERIOR_ACTIVE.
	 * @param object $controller Action's controller object.
	 * @param string $method Method name in the controller.
	 */
	public function addSuperiorActiveActions($package, $controller, $method) {
		$this->superiorActiveActions[] = array("package" => $package, "controller" => $controller, "method" => $method);
	}
	

	/**
	 * Returns true if any superior action is active
	 * @return <bool> true if any superior action is active
	 */
	public function checkSuperiorActiveActions($actualAction) {
		foreach ($this->superiorActiveActions as $action) {
			if ($action["package"] == $actualAction["package"] 
				&& $action["controller"] == $actualAction["controller"]->getName() 
				&& $action["method"] == $actualAction["method"]) {
				return true;
			}
		}
		return false;
	}
	
	public function getActionPackage() {
		return $this->action["package"];
	}
	
	public function getActionController() {
		return $this->action["controller"];
	}
	
	public function getActionMethod() {
		return $this->action["action"];
	}
	
	public function getActionItem() {
		return $this->action["item"];
	}
	
	private function makeAutoLink() {
		return $this->action["package"] . '::' . $this->action["controller"] . '::'
		. $this->action["action"] . (isset($this->action["item"]) ? '::' . $this->action["item"] : '');
	}
}


?>
