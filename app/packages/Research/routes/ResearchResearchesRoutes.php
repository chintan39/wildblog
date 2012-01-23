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


class ResearchResearchesRoutes extends AbstractPagesRoutes {
	
	public $order = 2;				// order of the Routes (0-10 asc)

	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {
		AbstractAdminRoutes::setRouter($this);

		Router::registerAction($this, 'actionDetail')
			->addRuleUrl('research/[url]/$')
			->setTemplate('researchDetail');
		
		$adminActionsPrefix = isset($this->actionsPrefix) ? $this->actionsPrefix : 'admin/' . strtolower($this->package) . '/' . strtolower($this->name) . '/';

		Router::registerAction($this, 'actionViewResults') 
			->addRuleUrl($adminActionsPrefix . 'view-results/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultList')
			->setPermission(Permission::$ADMIN);
		
		Router::registerAction($this, 'actionViewResultsCSV')
			->addRuleUrl($adminActionsPrefix . 'view-results-csv/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultListCsv')
			->setPermission(Permission::$ADMIN);
		
	}

	
}

?>