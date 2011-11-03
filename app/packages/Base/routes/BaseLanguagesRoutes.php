<?php

class BaseLanguagesRoutes extends AbstractNodesRoutes {
	
	public $order = 7;				// order of the Routes (0-10)
	

	public function setRouter() {
		
		AbstractAdminRoutes::setRouter($this);
	}
	
}


?>
