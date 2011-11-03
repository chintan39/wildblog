<?php

/**
 * Package covering basic content.
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class BasicPackage extends Package {
	
	var $icon="basic";

	public function setDefaultConfig() {
		Config::Set('BASIC_HOMEPAGE_ARTICLE_ID', false, null, Config::INT, true);
		Config::Set('BASIC_SHORT_CONTACT_ARTICLE_ID', false, null, Config::INT, true);
		Config::Set('BASIC_PERSONAL_INFO_ARTICLE_ID', false, null, Config::INT, true);
		Config::Set('BASIC_FOOTER_ARTICLE_ID', false, null, Config::INT, true);
		Config::Set('BASIC_ARTICLES_ADD_ANCHORS_INTO_SITEMENU', false, null, Config::BOOL, false);
		Config::Set('BASIC_PACKAGE_ORDER', 3, null, Config::INT, true);
		Config::Set('BASIC_PACKAGE_LANGUAGE_SUPPORT', false, null, Config::BOOL, false);
		Config::Set('BASIC_PACKAGE_ALLOW', true, null, Config::BOOL, false);
		Config::Set('BASIC_NEWS_RECENT_COUNT', 4, null, Config::INT, true);
		Config::Set('BASIC_NEWS_SORTABLE', false, null, Config::BOOL, true);
		Config::Set('BASIC_NEWS_PRIMARY_TAG', false, null, Config::INT, true);
		Config::Set('BASIC_NEWS_LIMIT', 6, null, Config::INT, true);
	}
	
	/**
	 * This method is called by the menu handler and collects links from
	 * all packages to put all links from the left menu together.
	 * @return array Link array, that should be visible in the left menu
	 * in the backend.
	 */
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->name, "Articles", "actionListing"), 
			'label' => tg($this->name . " package"), 
			'title' => tg("articles, news, contact form"), 
			'image' => $this->getIcon(),
			'action' => array(
				"package" => $this->name, 
				"controller" => "Articles", 
				"action" => "actionListing")));
		$link->setOrder($this->order);
		return array($link);
	}
	
}

?>