<?php
/**
 * Package covering calendar events (periodic or one-shot).
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on).
 */
class CalendarPackage extends Package {
    
	var $icon="calendar";

	public function setDefaultConfig() {
		Config::Set("CALENDAR_PACKAGE_ORDER", 6, null, Config::INT, true);
		Config::Set("CALENDAR_PACKAGE_LANGUAGE_SUPPORT", false, null, Config::BOOL, false);
		Config::Set("CALENDAR_PACKAGE_ALLOW", false, null, Config::BOOL, false);
	}
	
	/**
	 * This method is called by the menu handler and collects links from
	 * all packages to put all links from the left menu together.
	 * @return array Link array, that should be visible in the left menu
	 * in the backend.
	 */
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->name, "Events", "actionListing"), 
			'label' => tg($this->name . ' ' . tg('package')), 
			'title' => tg('events, calendar tags'), 
			'image' => $this->getIcon(),
			'action' => array(
				"package" => $this->name, 
				"controller" => "Events", 
				"action" => "actionListing")));
		$link->setOrder($this->order);
		return array($link);
	}
	
}

?>