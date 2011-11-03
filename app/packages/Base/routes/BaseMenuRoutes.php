<?php

class BaseMenuRoutes extends AbstractBasicRoutes {
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */

	public function setRouter() {
		
		Router::registerSubaction($this, 'subactionAdminMenuLeft')
			->addRuleUrl('admin')
			->setBranch(Themes::BACK_END)
			->setTemplate('part.menuLeft')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);
	}

	
}

?>
