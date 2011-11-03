<?php
/**
 * Package covering link building actions and tools.
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class NewsletterPackage extends Package {
    
	var $icon = 'newsletter';

	public function setDefaultConfig() {
		Config::Set('NEWSLETTER_PACKAGE_ORDER', 7, null, Config::INT, true);
		Config::Set('NEWSLETTER_PACKAGE_LANGUAGE_SUPPORT', false, null, Config::BOOL, false);
		Config::Set('NEWSLETTER_PACKAGE_ALLOW', true, null, Config::BOOL, false);
		Config::Set('NEWSLETTER_ALLOW_CHECK_ADDRESSES', true, null, Config::BOOL, false);
		Config::Set('NEWSLETTER_SEND_LIMIT', 2, null, Config::INT, true);
	}
	
	/**
	 * This method is called by the menu handler and collects links from
	 * all packages to put all links from the left menu together.
	 * @return array Link array, that should be visible in the left menu
	 * in the backend.
	 */
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->name, 'Messages', 'actionListing'), 
			'label' => tg($this->name . ' ' . tg('package')), 
			'title' => tg('partner links, contacts'), 
			'image' => $this->getIcon(),
			'action' => array(
				'package' => $this->name, 
				'controller' => 'Messages', 
				'action' => 'actionListing')));
		$link->addSuperiorActiveActions($this->name, 'Messages', 'actionNew');
		$link->addSuperiorActiveActions($this->name, 'Messages', 'actionEdit');
		$link->addSuperiorActiveActions($this->name, 'Messages', 'actionSend');
		$link->addSuperiorActiveActions($this->name, 'Messages', 'actionCheckSending');
		$link->setOrder($this->order);
		return array($link);
	}
	
}

?>