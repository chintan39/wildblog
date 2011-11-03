<?php

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
		if (Permission::getActualUserPermissionLevel() == Permission::$CONTENT_ADMIN) {
			$items->setQualification(array("onlysafemode" => array("safemode = ?" => array(1)))); // we overload filters - no qualifications are used
		}
		$filters = new BaseFiltersModel();
		$condition = '__field__';
		$valueAdjust = '.*__value__.*';
		$filters->addMetaData(ModelMetaItem::create('key')
			->setLabel('Key')
			->setType(Form::FORM_INPUT_TEXT)
			->setValueConditionPattern($condition)
			->setValueAdjustPattern($valueAdjust));
		$filters->values['key'] = '';
		$filters->addMetaData(ModelMetaItem::create('text')
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
		if (Permission::getActualUserPermissionLevel() == Permission::$CONTENT_ADMIN) {
			$item->setMetadata('safemode', 'isEditable', ModelMetaItem::NEVER);
		}
	}
	
	protected function getListingButtons() {
		$buttons = array(
			ItemCollection::BUTTON_EDIT => 'actionEdit', 
		);
		return $buttons;
	}
}

?>