<?php

class BasePackage extends Package {
	
	var $icon='settings';

	public function setDefaultConfig() {
		
		Config::Set('BASE_DICTIONARY_ADD_ON_NO_ENTRY', true, null, Config::BOOL, false);
		Config::Set('BASE_PACKAGE_ORDER', 8, null, Config::INT, true);
		Config::Set('BASE_PACKAGE_LANGUAGE_SUPPORT', false, null, Config::BOOL, false);
		Config::Set('BASE_PACKAGE_ALLOW', true, null, Config::BOOL, false);

	}

	
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->name, 'Dictionary', 'actionListing'), 
			'label' => tg($this->name . ' package'), 
			'title' => tg('static texts, database, users, languages'), 
			'image' => $this->getIcon(),
			'action' => array(
				'package' => $this->name, 
				'controller' => 'Dictionary', 
				'action' => 'actionListing')));
		$link->setOrder($this->order);
		return array($link);
	}
	
}

?>