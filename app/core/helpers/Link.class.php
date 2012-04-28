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
 * Request location
 */
class RequestLocation {
	var $package, $controller, $method, $item;
}


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
		$this->link = isset($initArray['link']) ? $initArray['link'] : (isset($initArray['action']) ? Request::getLinkArray($initArray['action']): '');
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
	 * Returns the link in string format
	 */
	public function getLink() {
		return $this->link;
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
				&& $action["controller"] == $actualAction["controller"] 
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
