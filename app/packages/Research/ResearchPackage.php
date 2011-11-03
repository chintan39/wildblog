<?php
/**
 * Package covering reasearch of visitors' opinion
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class ResearchPackage extends Package {
    
	var $icon = 'references';

	public function setDefaultConfig() {
		Config::Set('RESEARCH_PACKAGE_ORDER', 8, null, Config::INT, true);
		Config::Set('RESEARCH_PACKAGE_LANGUAGE_SUPPORT', false, null, Config::BOOL, false);
		Config::Set('RESEARCH_PACKAGE_ALLOW', true, null, Config::BOOL, false);
	}

	/**
	 * This method is called by the menu handler and collects links from
	 * all packages to put all links from the left menu together.
	 * @return array Link array, that should be visible in the left menu
	 * in the backend.
	 */
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->name, 'Researches', 'actionListing'), 
			'label' => tg($this->name . ' ' . tg('package')), 
			'title' => tg('research of visitors\' opinion'), 
			'image' => $this->getIcon(),
			'action' => array(
				'package' => $this->name, 
				'controller' => 'Researches', 
				'action' => 'actionListing')));
		$link->setOrder($this->order);
        return array($link);
	}

}

?>