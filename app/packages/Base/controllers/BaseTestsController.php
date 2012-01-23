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


class BaseTestsController extends AbstractDefaultController {
	
	public $order = 9;				// order of the controller (0-10)

	/**
	 * Action Listing - table lists
	 * @param mixed $args arguments to this action
	 */
	public function actionListing($args) {
		$items = new ItemCollection($this->getMainListIdentifier(), $this);
		$items->setLimit(0);
		$items->loadCollection();
		$items->addButtons(array(ItemCollection::BUTTON_EDIT => 'actionRun'));
		$this->assign($items->getIdentifier(), $items);
	}
	

	/**
	 * Action edit - this will show the construct SQL for the model
	 * @param mixed $args name of the module
	 */ 
	public function actionRun($item) {
		$testName = $item->id;
		$case = new $testName();
		$results = $case->run();
		$this->assign("results", $results);
	}


	/**
	 * Links to admin Menu Left
	 * @return array Links
	 */
	public function getLinksAdminMenuLeft() {
		$listLink = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, "actionListing"), 
			'label' => $this->name, 
			'title' => tg('tests'), 
			'image' => $this->getIcon(), 
			'action' => array(
				"package" => $this->package, 
				"controller" => $this->name, 
				"action" => "actionListing")));
		$listLink->addSuperiorActiveActions($this->package, $this->name, "actionRun");
		$listLink->setOrder($this->order);
		
		return array($listLink);
	}
	

}

?>