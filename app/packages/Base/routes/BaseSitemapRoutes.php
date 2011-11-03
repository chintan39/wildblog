<?php

class BaseSitemapRoutes extends AbstractBasicRoutes {

	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {
		
		Router::registerAction($this, 'actionSitemap') 
			->addRuleUrl('sitemap/')
			->setTemplate('sitemap');

		Router::registerAction($this, 'actionAvailableLinks') 
			->addRuleUrl('site-links/')
			->setTemplate('sitelinks');

		Router::registerAction($this, 'actionSitemapXML')
			->addRuleUrl('sitemap.xml')
			->setTemplate('sitemap.xml');
	}
	

}

?>