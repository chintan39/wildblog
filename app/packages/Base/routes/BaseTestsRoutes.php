<?php

class BaseTestsRoutes extends AbstractDefaultRoutes {
	
	public $order = 9;				// order of the Routes (0-10)
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 * TODO: if two models are connected to one table, do changes/creating only once
	 */
	public function setRouter() {
		
		parent::setRouter();
		
		Router::registerAction($this, 'actionListing')
			->addRuleUrl('admin/tests/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultList')
			->setPermission(Permission::$ADMIN);
		
		Router::registerAction($this, 'actionRun')
			->addRuleUrl('admin/tests/run/[id]/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('testResult')
			->setPermission(Permission::$ADMIN);

	}


}

?>