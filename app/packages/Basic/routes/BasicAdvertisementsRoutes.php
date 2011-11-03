<?php

class BasicAdvertisementsRoutes extends AbstractPagesRoutes {
	
	public $order = 6;				// order of the Routes (0-10 asc)
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {
		AbstractAdminRoutes::setRouter($this);

		Router::registerSubaction($this, 'subactionMainAdvertisements') 
			->setTemplate('part.advertisements');

	}


}

?>