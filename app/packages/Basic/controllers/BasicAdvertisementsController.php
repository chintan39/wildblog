<?php

class BasicAdvertisementsController extends AbstractPagesController {
	
	public $order = 6;				// order of the controller (0-10 asc)
	
	/**
	 * Request handler
	 * Articles structure generation. 
	 */
	public function subactionMainAdvertisements($args) {
		Benchmark::log("Begin of creating AdvertisementsController::subactionMainAdvertisements");
		$mainAdvertisements = $this->loadCache('mainAdvertisements');
		if (!$mainAdvertisements) {
			$mainAdvertisements = new ItemCollection("mainAdvertisements", $this);
			$mainAdvertisements->loadCollection();
			$this->saveCache('mainAdvertisements', $mainAdvertisements, array('BasicAdvertisementsModel'));
		}
		$this->assign($mainAdvertisements->getIdentifier(), $mainAdvertisements);
		Benchmark::log("End of creating AdvertisementsController::subactionMainAdvertisements");
	}

}

?>