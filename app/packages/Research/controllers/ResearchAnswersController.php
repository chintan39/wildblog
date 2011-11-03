<?php

class ResearchAnswersController extends AbstractDefaultController {
	
	public $order = 2;				// order of the controller (0-10 asc)

	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}

	
}

?>