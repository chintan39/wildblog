<?php

class AbstractProductionUnitsController extends AbstractCodebookController {
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}

}

?>