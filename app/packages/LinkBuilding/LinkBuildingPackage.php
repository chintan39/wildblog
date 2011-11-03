<?php
/**
 * Package covering link building actions and tools.
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class LinkBuildingPackage extends Package {
    
	var $icon="link_building";

	public function setDefaultConfig() {
		Config::Set("LINKBUILDING_PACKAGE_ORDER", 7, null, Config::INT, true);
		Config::Set("LINKBUILDING_PACKAGE_LANGUAGE_SUPPORT", false, null, Config::BOOL, false);
		Config::Set("LINKBUILDING_PACKAGE_ALLOW", true, null, Config::BOOL, false);
	}
	
	/**
	 * This method is called by the menu handler and collects links from
	 * all packages to put all links from the left menu together.
	 * @return array Link array, that should be visible in the left menu
	 * in the backend.
	 */
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->name, "Partners", "actionListing"), 
			'label' => tg($this->name . ' ' . tg('package')), 
			'title' => tg('partner links, contacts'), 
			'image' => $this->getIcon(),
			'action' => array(
				"package" => $this->name, 
				"controller" => "Partners", 
				"action" => "actionListing")));
		$link->setOrder($this->order);
		return array($link);
	}
	
}

?>