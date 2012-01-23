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
 * Package covering basic content.
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class BasicPackage extends Package {
	
	var $icon="basic";

	public function setDefaultConfig() {
		Config::Set('BASIC_PACKAGE_ORDER', 3, null, Config::INT, true);
		Config::Set('BASIC_PACKAGE_LANGUAGE_SUPPORT', false, null, Config::BOOL, false);
		Config::Set('BASIC_PACKAGE_ALLOW', true, null, Config::BOOL, false);
		Config::Set('BASIC_HOMEPAGE_ARTICLE_ID', false, 'ID of the homepage article', Config::INT, true);
		Config::Set('BASIC_SHORT_CONTACT_ARTICLE_ID', false, 'ID of the article with contacts usualy displayed in footer/sidebar', Config::INT, true);
		Config::Set('BASIC_PERSONAL_INFO_ARTICLE_ID', false, 'ID of the article with personal data usualy displayed in footer/sidebar', Config::INT, true);
		Config::Set('BASIC_FOOTER_ARTICLE_ID', false, 'ID of the article used for various purposes usualy displayed in footer/sidebar', Config::INT, true);
		Config::Set('BASIC_ARTICLES_ADD_ANCHORS_INTO_SITEMENU', false, null, Config::BOOL, false);
		Config::Set('BASIC_NEWS_RECENT_COUNT', 4, 'Number of recent news displayed in sidebar', Config::INT, true);
		Config::Set('BASIC_NEWS_SORTABLE', 'If news can be sorted', null, Config::BOOL, true);
		Config::Set('BASIC_NEWS_PRIMARY_TAG', false, null, Config::INT, true);
		Config::Set('BASIC_NEWS_LIMIT', 6, 'Number of news displayed in sidebar', Config::INT, true);
		Config::Set('BASIC_ARTICLES_CONTACT_FORM', false, 'Colon-separated article ID values, where contact form should be displayed', Config::STRING, true);
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