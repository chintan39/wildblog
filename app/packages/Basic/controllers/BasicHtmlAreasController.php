<?php

class BasicHtmlAreasController extends AbstractPagesController {
	
	public $order = 7;				// order of the controller (0-10 asc)
	
	/**
	 * Request handler
	 * Articles structure generation. 
	 */
	public function subactionMainHtmlAreas($args) {
		Benchmark::log("Begin of creating AdvertisementsController::subactionMainHtmlAreas");
		$mainAreas = $this->loadCache('mainAreas');
		if (!$mainAreas) {
			$mainAreas = new ItemCollection("mainAreas", $this);
			$mainAreas->loadCollection();
			$this->saveCache('mainAreas', $mainAreas, array('BasicHtmlAreasModel'));
		}
		$this->assign($mainAreas->getIdentifier(), $mainAreas);
		Benchmark::log("End of creating AdvertisementsController::subactionMainHtmlAreas");
	}

}

?>