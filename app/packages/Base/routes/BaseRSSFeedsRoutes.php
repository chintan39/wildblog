<?php

/**
 * Handles RSS feeds export to the HTML.
 */
class BaseRSSFeedsRoutes extends AbstractBasicRoutes {
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {
		
		parent::setRouter();
		
		Router::registerSubaction($this, 'subactionRSSFeeds')
			->setTemplate('part.rssFeeds');
		
	}
	

}

?>
