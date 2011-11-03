<?php
/**
 * Package covering Global reading stuff (forms, etc.).
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class GlobalReadingPackage extends Package {
    
	var $icon="application";

	public function setDefaultConfig() {
		Config::Set("GLOBALREADING_PACKAGE_ORDER", 6);
		Config::Set("GLOBALREADING_PACKAGE_LANGUAGE_SUPPORT", false);
		Config::Set("GLOBALREADING_PACKAGE_ALLOW", true);
	}

	/**
	 * This method is called by the menu handler and collects links from
	 * all packages to put all links from the left menu together.
	 * @global object $req Request object.
	 * @return array Link array, that should be visible in the left menu
	 * in the backend.
	 */
	public function getLinksAdminMenuLeft() {
		global $req;
		$link = new Link(array(
			'link' => $req->getLinkSimple($this->name, "Forms", "actionListing"), 
			'label' => tg($this->name . ' ' . 'package'), 
			'title' => tg('global reading forms'), 
			'image' => $this->getIcon(),
			'action' => array(
				'package' => $this->name, 
				'controller' => 'Forms', 
				'action' => 'actionListing')));
		$link->setOrder($this->order);
        return array($link);
	}

}

?>