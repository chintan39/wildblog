<?php

class AbstractNodesController extends AbstractDefaultController {
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}
	
}

?>