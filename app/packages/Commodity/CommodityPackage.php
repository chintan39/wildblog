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
 * Package covering link building actions and tools.
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class CommodityPackage extends Package {
    
	var $icon="product";

	public function setDefaultConfig() {
		Config::Set("COMMODITY_PACKAGE_ORDER", 9, null, Config::INT, true);
		Config::Set("COMMODITY_PACKAGE_LANGUAGE_SUPPORT", false, null, Config::BOOL, false);
		Config::Set("COMMODITY_PACKAGE_ALLOW", true, null, Config::BOOL, false);
	}

	/**
	 * This method is called by the menu handler and collects links from
	 * all packages to put all links from the left menu together.
	 * @return array Link array, that should be visible in the left menu
	 * in the backend.
	 */
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			//'link' => Request::getLinkSimple($this->name, "Products", "actionListing"), 
			'label' => tg($this->name . ' ' . tg('package')), 
			'title' => tg('products, manofacturers'), 
			'image' => $this->getIcon(),
			'action' => array(
				"package" => $this->name, 
				"controller" => "Products", 
				"action" => "actionListing")));
		$link->setOrder($this->order);
        return array($link);
	}

}

?>