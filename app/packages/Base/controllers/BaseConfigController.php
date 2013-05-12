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


class BaseConfigController extends AbstractDefaultController {

	public $order = 1;				// order of the controller (0-10)
	

	/**
	 * Left Menu Links definition
	 */
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}
	
	/**
	 * Request handler
	 * List of items will be stored in ItemCollection object, then data from the collection 
	 * will be printed with specified buttons, paging, etc.
	 */
	public function actionListing($args) {
		$items = new ItemCollection($this->getMainListIdentifier(), $this, 'BaseConfigAllModel');
		//if (Permission::getActualUserPermissionLevel() == Permission::$CONTENT_ADMIN) {
		//	$items->setQualification(array("onlysafemode" => array(new ItemQualification("safemode = ?", array(1))))); // we overload filters - no qualifications are used
		//}
		$filters = new BaseFiltersModel();
		$condition = '__field__';
		$valueAdjust = '.*__value__.*';
		$filters->addMetaData(AtributesFactory::create('key')
			->setLabel('Key')
			->setType(Form::FORM_INPUT_TEXT)
			->setValueConditionPattern($condition)
			->setValueAdjustPattern($valueAdjust));
		$filters->values['key'] = '';
		$filters->addMetaData(AtributesFactory::create('text')
			->setLabel('Value')
			->setType(Form::FORM_INPUT_TEXT)
			->setValueConditionPattern($condition)
			->setValueAdjustPattern($valueAdjust));
		$filters->values['text'] = '';
		$items->setFilters($filters);
		//$items->setDefaultFilters();
		$items->handleFilters();
		$items->forceLanguage(Language::get(Themes::FRONT_END));
		$items->loadCollection();

		$buttons = $this->getListingButtons();
		
		$items->addButtons($buttons);
		
		$this->assign($items->getIdentifier(), $items);
		$this->assign('title', tg('List of ' . strtolower($this->name)));
		$this->assign('help', tg($this->description));
		
		// Top menu
		$this->addTopMenu();
	}


	/**
	 * Hide "safemode" form item for content admins.
	 */
	protected function actionEditAdjustItem(&$item) {
		//if (Permission::getActualUserPermissionLevel() == Permission::$CONTENT_ADMIN) {
		//	$item->setMetadata('safemode', 'isEditable', ModelMetaItem::NEVER);
		//}
	}
	
	protected function getListingButtons() {
		$buttons = array(
			ItemCollection::BUTTON_EDIT => 'actionEdit', 
		);
		return $buttons;
	}
}

?>