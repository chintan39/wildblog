<?php

class FAQPackage extends Package {
		
	var $icon="comment";
	
	public function setDefaultConfig() {
		Config::Set("FAQ_PACKAGE_ORDER", 6, null, Config::INT, true);
		Config::Set("FAQ_PACKAGE_LANGUAGE_SUPPORT", false, null, Config::BOOL, false);
		Config::Set("FAQ_PACKAGE_ALLOW", true, null, Config::BOOL, false);
	}
	
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->name, "Questions", "actionListing"), 
			'label' => tg($this->name . " package"), 
			'title' => tg("questions, answers"), 
			'image' => $this->getIcon(),
			'action' => array(
				"package" => $this->name, 
				"controller" => "Questions", 
				"action" => "actionListing")));
		$link->setOrder($this->order);
		return array($link);
	}
}

?>