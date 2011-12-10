<?php


class AbstractStructuredCodebookController extends AbstractCodebookController {
	
	/**
	 * Request handler
	 * List of items will be stored in ItemCollection object, then data from the collection 
	 * will be printed with specified buttons, paging, etc.
	 */
	public function actionListing($args) {

		$items = new ItemCollectionTree($this->getMainListIdentifier(), $this);
		$items->setPagingAjax(true);
		$items->setQualification(null); // we overload filters - no qualifications are used
		$items->setDefaultFilters();
		$items->handleFilters();
		$items->forceLanguage(Language::get(Themes::FRONT_END));
		$items->treeBase(ItemCollectionTree::treeRoot);
		$items->treePull(ItemCollectionTree::treeAncestors | ItemCollectionTree::treeDescendants);
		$items->loadCollection();

		$buttons = $this->getListingButtons();
		
		$items->addButtons($buttons);
		
		$this->assign($items->getIdentifier(), $items);
		$this->assign('title', tg('List of ' . strtolower($this->name)));
		$this->assign('help', tg($this->description));
		
		// Top menu
		$this->addTopMenu();
	}
}

?>