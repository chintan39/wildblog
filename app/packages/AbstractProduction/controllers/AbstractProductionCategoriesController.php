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


class AbstractProductionCategoriesController extends AbstractPagesController {
	
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}
	
	
	public function actionCategoryDetail($args) {
		
		// product detail processing
		$category = $args;
		$category->addNonDbProperty("products");
		$category->products = $this->getPackageObject()->getController("Products")->getProducts($category->id);

		// navigation
		$navigation = new LinkCollection();
		$home = Request::getLinkHomePage();
		$navigation->addLink($home);
		$this->assign("navigation", $navigation->getLinks());
		
		// assign to template
		$this->assign("title", $category->title);
		$this->assign("pageTitle", $category->title . ' | ' . tp("Project Title Short"));
		$this->assign("category", $category);
		
		// show template
		//$this->display('categoryDetail');
	}
	
	/**
	 * Request handler
	 * Categories structure generation. 
	 */
	public function subactionCategoriesTree($args) {
		Benchmark::log("Begin of creating CategoriesController::subactionCategoriesTree");
		$categoriesTree = new ItemCollectionTree("categoriesTree", $this);
		$categoriesTree->setLinks("actionCategoryDetail");
		$categoriesTree->setTreeHigh(3);
		$categoriesTree->loadCollection();
		$categoriesTree->addLinks();
		$this->assign($categoriesTree->getIdentifier(), $categoriesTree);
		Benchmark::log("End of creating CategoriesController::subactionCategoriesTree");
	}
		
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array(), array('actionCategoryDetail' => tg('Product category')));
	}
}

?>