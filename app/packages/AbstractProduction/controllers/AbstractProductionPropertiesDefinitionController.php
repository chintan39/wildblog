<?php

class AbstractProductionPropertiesDefinitionController extends AbstractPropertiesDefinitionController {

	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}

}

?>
