<?php

/**
 * Handles RSS feeds export to the HTML.
 */
class BaseRSSFeedsController extends AbstractBasicController {
	
	public function subactionRSSFeeds($args) {
		$results = array();
		foreach (Environment::getPackages() as $package) {
			// Try to get links from all controllers in the package
			foreach ($package->getControllers() as $controller) {
				if (method_exists($controller, 'getRSSFeed')) {
					$results = array_merge($results, $controller->getRSSFeed());
				}
			}
		}
		
		$this->assign("rssFeeds", $results);
	}
	

}

?>
