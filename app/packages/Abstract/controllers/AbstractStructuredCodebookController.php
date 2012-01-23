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