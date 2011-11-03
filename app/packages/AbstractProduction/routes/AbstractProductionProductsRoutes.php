<?php

class AbstractProductionProductsRoutes extends AbstractPagesRoutes {
	
	var $productsCategoriesModelName = 'AbstractProductionCategoriesModel';
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and controller actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {

		AbstractAdminRoutes::setRouter($this);
		
		Router::registerAction($this, 'actionProductsList') 
			->addRuleUrl('products/$')
			->setTemplate('productList');

		Router::registerAction($this, 'actionDetail') 
			->addRuleUrl('prod/[url]/$')
			->setTemplate('productDetail');

		Router::registerAction($this, 'actionRss') 
			->addRuleUrl('products-feed/$')
			->setTemplate('rss');
	}
	
}

?>