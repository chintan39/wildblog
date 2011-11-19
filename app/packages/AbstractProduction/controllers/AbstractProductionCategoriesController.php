<?php

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
		$home = new Link(array(
			'link' => Request::getLinkHomePage(), 
			'label' => tg('Homepage'), 
			'title' => tg('Homepage')));
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
		return $this->getItemsLinksDefault(array('actionCategoryDetail' => tg('Product category')), array());
	}
}

?>