<?php

class BaseHitsController extends AbstractDefaultController {

	public $order = 4;				// order of the controller (0-10)
	

	/**
	 * Left Menu Links definition
	 */
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}
	
	
}

?>