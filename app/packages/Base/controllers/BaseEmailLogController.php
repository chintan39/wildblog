<?php

class BaseEmailLogController extends AbstractDefaultController {
	
	public $order = 4;				// order of the controller (0-10)
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeftListing($this);
	}
	
}

?>