<?php

class BasicTagsRoutes extends AbstractNodesRoutes {
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {

		AbstractAdminRoutes::setRouter($this);

		Router::registerAction($this, 'actionArticlesTagDetail')
			->addRuleUrl('art-tag/[url]/$')
			->setTemplate('articlesList');

		Router::registerAction($this, 'actionNewsTagDetail')
			->addRuleUrl('news-tag/[url]/$')
			->setTemplate('newsList');


	}
	

	
}

?>
