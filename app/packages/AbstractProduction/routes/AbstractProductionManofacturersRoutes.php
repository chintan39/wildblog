<?php

class AbstractProductionManofacturersRoutes extends AbstractNodesRoutes {
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and controller actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {

		AbstractAdminRoutes::setRouter($this);
		
		Router::registerAction($this, 'actionManofacturersList')
			->addRuleUrl('manofacturers/$')
			->setTemplate('manofacturersList');
		
		Router::registerAction($this, 'actionManofacturerDetail')
			->addRuleUrl('manofacturers/[url]/$')
			->setTemplate('manofacturerDetail');
		
		Router::registerSubaction($this, 'subactionManofacturersList') 
			->setTemplate('part.manofacturersList');
	}

}

?>