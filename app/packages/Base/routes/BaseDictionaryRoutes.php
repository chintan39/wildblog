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


class BaseDictionaryRoutes extends AbstractDefaultRoutes {

	public $order = 2;				// order of the Routes (0-10)
	
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {
		
		AbstractAdminRoutes::setRouter($this, Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
		Router::registerAction($this, 'actionAnalyze')
			->addRuleUrl('admin/dictionary/analyze/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('index')
			->setPermission(Permission::$ADMIN);
		
		Router::registerAction($this, 'actionAnalyzeRemove')
			->addRuleUrl('admin/dictionary/analyze-remove/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('index')
			->setPermission(Permission::$ADMIN);
		
		Router::registerAction($this, 'actionAnalyzeAdd')
			->addRuleUrl('admin/dictionary/analyze-add/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('index')
			->setPermission(Permission::$ADMIN);
		
	}
	

	
}

?>