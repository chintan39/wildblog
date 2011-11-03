<?php

class AbstractPropertiesOptionsController extends AbstractCodebookController {
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and controller actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */

	 public function setRouter() {

		AbstractAdminController::setRouter($this);
		
	}
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}

}

?>