<?php

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