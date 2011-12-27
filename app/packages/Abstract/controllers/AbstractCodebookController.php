<?php


class AbstractCodebookController extends AbstractDefaultController {
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}

}

?>