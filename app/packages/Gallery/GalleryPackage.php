<?php
/**
 * Package covering Image handling and Image galleries composing.
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on).
 */
class GalleryPackage extends Package {
    
	var $icon="gallery";

	public function setDefaultConfig() {
		Config::Set("GALLERY_PACKAGE_ORDER", 7, null, Config::INT, true);
		Config::Set("GALLERY_PACKAGE_LANGUAGE_SUPPORT", false, null, Config::BOOL, false);
		Config::Set("GALLERY_PACKAGE_ALLOW", true, null, Config::BOOL, false);
		Config::Set("GALLERY_GALLERIES_SITE_COUNT", 6, null, Config::INT, true);
	}
	
	/**
	 * This method is called by the menu handler and collects links from
	 * all packages to put all links from the left menu together.
	 * @return array Link array, that should be visible in the left menu
	 * in the backend.
	 */
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->name, "Galleries", "actionListing"), 
			'label' => tg($this->name . ' ' . 'package'), 
			'title' => tg('image galleries'), 
			'image' => $this->getIcon(),
			'action' => array(
				"package" => $this->name, 
				"controller" => "Galleries", 
				"action" => "actionListing")));
		$link->setOrder($this->order);
		return array($link);
	}
	
}

?>
