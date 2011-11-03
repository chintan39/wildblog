<?php

class AbstractProductionProductsController extends AbstractPagesController {
	
	var $productsCategoriesModelName = 'AbstractProductionCategoriesModel';
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}
	
	public function actionDetail($args) {
		
		// product detail processing
		$product = $args;
		$product->loadProperties();
		$product->addNonDbProperty("categoryItems");
		$productsCategoriesModelName = $this->getPackageObject()->getController("Categories")->model;
		$product->categoryItems = $product->Find($productsCategoriesModelName);

		// navigation
		$navigation = new LinkCollection();
		$home = new Link(array(
			'link' => Request::getLinkHomePage(), 
			'label' => tg('Homepage'), 
			'title' => tg('Homepage')));
		$navigation->addLink($home);
		if ($product->categoryItems) {
			$categoryLink = new Link(array(
				'link' => Request::getLinkItem($this->package, "Categories", "actionCategoryDetail", $product->categoryItems[0]), 
				'label' => $product->categoryItems[0]->title, 
				'title' => $product->categoryItems[0]->title));
			$navigation->addLink($categoryLink);
		}
		$this->assign("navigation", $navigation->getLinks());
		
		// assign to template
		$this->assign("title", $product->title);
		$this->assign("pageTitle", $product->title . ' | ' . tp("Project Title Short"));
		$this->assign("product", $product);
		
		// show template
		//$this->display('productDetail');
	}
	
	/**
	 * Products List action
	 */
	public function actionProductsList($args) {
		$items = $this->getProducts();
		$this->assign("title", tp("Project Title"));
		$this->assign($items->getIdentifier(), $items);
		//$this->display('productList');
	}
	
	public function getProducts($category=false, $manofacturer=false) {
		$items = new ItemCollection("products", $this);
		$items->setSorting(array(array("column" => "inserted", "direction" => "DESC")));
		if ($category) {
			$categoryModel = $this->getPackageObject()->getController("Categories")->model;
			$cat = new $categoryModel($category);
			$productIds = $cat->Find($this->model);
			$values = array();
    		foreach ($productIds as $p) {
    			$values[] = $p->id;
    		}
    		$filters = " id in (?" . str_repeat(", ?", count($values)-1) . ")";
			$items->setQualification(array("category" => array($filters => $values)));
		}
		if ($manofacturer) {
			$items->setQualification(array("manofacturer" => array("manofacturer = ?" => $manofacturer)));
		}
		$items->setSorting(array(array('column' => 'inserted', 'direction' => 'desc')));
		$items->setLimit(6);
		$items->loadCollection();
		$items->addLinks(null, "actionDetail");
		if ($items->data['items']) {
			foreach ($items->data['items'] as $i) {
				$i->loadProperties();
			}
		}
		return $items;
	}
	
	/** 
	 * Searching method returns all items that should be found.
	 * @return array of object
	 */
	public function getSearchItems($text) {
		$searchProducts = new ItemCollection("searchProducts", $this);
		$searchProducts->setLimit(10);
		$searchProducts->setLinks("actionDetail");
		$searchProducts->setQualification(array("fulltext" => array("title LIKE ? OR text LIKE ?" => array('%' . $text . '%', '%' . $text . '%'))));
		$searchProducts->loadCollection();
		$searchProducts->addLinks();
		if ($searchProducts->data["items"]) {
			foreach ($searchProducts->data["items"] as $key => $item) {
				$item->addNonDbProperty("preview");
				$item->preview = Utilities::truncate(strip_tags($item->text), 250);
			}
		}
		return $searchProducts;
	}

	/**
	 * RSS action
	 */
	public function actionRss($args) {
		$items = new ItemCollection("products", $this);
		$items->setLimit(20);
		$items->setSorting(array(array("column" => "updated", "direction" => "DESC")));
		$items->loadCollection();
		
		if ($items->data["items"]) {
			foreach ($items->data["items"] as $key => $item) {
				$item->addNonDbProperty("published");
				$item->published = $item->updated;
			}
		}
		
		$items->addLinks(null, "actionDetail");
		$this->assign('items', $items);
		//$this->display('rss', false, 'Base');
	}
	
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionProductsList' => tg('List of products')), array('actionDetail' => tg('Product')));
	}
}

?>