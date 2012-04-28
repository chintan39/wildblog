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


class BasePackage extends Package {
	
	var $icon='settings';

	public function setDefaultConfig() {
		
		Config::Set('BASE_DICTIONARY_ADD_ON_NO_ENTRY', true, null, Config::BOOL, false);
		Config::Set('BASE_PACKAGE_ORDER', 8, null, Config::INT, true);
		Config::Set('BASE_PACKAGE_LANGUAGE_SUPPORT', false, null, Config::BOOL, false);
		Config::Set('BASE_PACKAGE_ALLOW', true, null, Config::BOOL, false);
		Config::Set('BASE_STORE_HITS_UNTIL_DATE', '0000-00-00 00:00:00', null, Config::STRING, false);
		Config::Set('BASE_DICTIONARY_FAST_TRANSLATE_URL', false, null, Config::BOOL, false);

	}

	
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			//'link' => Request::getLinkSimple($this->name, 'Dictionary', 'actionListing'), 
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