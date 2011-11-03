<?php

/**
 * Handles searching through the content.
 */
class BaseSearchController extends AbstractBasicController {
	
	
	public function actionSearch($args) {

		$text = Request::$get['s'];
	
		$results = array();
		foreach (Environment::getPackages() as $package) {
			// Try to get links from all controllers in the package
			foreach ($package->getControllers() as $controller) {
				$results[$package->getName().'.'.$controller->getName()] = $controller->getSearchItems($text);
			}
		}
		
		$this->assign("searchText", htmlspecialchars($text));
		$this->assign("title", tg("Searching: ") . htmlspecialchars('"' . $text . '"'));
		$this->assign("results", $results);

		//$this->display("search", Themes::FRONT_END);
	}
	

}

?>