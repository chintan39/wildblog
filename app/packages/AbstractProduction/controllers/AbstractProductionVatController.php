<?php

class AbstractProductionVatController extends AbstractCodebookController {
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}

}

?>