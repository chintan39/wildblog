<?php

class BaseMenuController extends AbstractBasicController {
	
	
	/**
	 * Request handler
	 * Admin Menu on the Left side controller. 
	 */
	public function subactionAdminMenuLeft($args) {
		Benchmark::log("Begin of creating MenuController::AdminMenuLeft");
		$adminMenuLeft = new LinkCollection();
		$adminMenuLeft->setIgnorePermissionDenied(true);
		$adminMenuLeft->getContentFromControllers("AdminMenuLeft");
		$adminMenuLeft->sort('order');
		$this->assign("adminMenuLeft", $adminMenuLeft->getLinks());
		Benchmark::log("End of creating MenuController::AdminMenuLeft");
	}
	
	
}

?>
