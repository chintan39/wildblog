<?php

class BaseDatabaseRoutes extends AbstractDefaultRoutes {
	
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
		
		Router::registerAction($this, 'actionConstruct')
			->addRuleUrl('admin/database/construct/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('dbcheck')
			->setPermission(Permission::$ADMIN);
		
		Router::registerAction($this, 'actionDbInit')
			->addRuleUrl('admin/database/init/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('init');
		
		Router::registerAction($this, 'actionDbTestCopy')
			->addRuleUrl('admin/database/testcopy/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('index')
			->setPermission(Permission::$ADMIN);
		
		if (dbConnection::getInstance()->adapter->dbtype == 'mysql') {
			Router::registerAction($this, 'actionDbCheck')
				->addRuleUrl('admin/database/check/$')
				->setBranch(Themes::BACK_END)
				->setTemplate('dbcheck')
				->setPermission(Permission::$ADMIN);
			
			Router::registerAction($this, 'actionDbCheckSimple')
				->addRuleUrl('admin/database/check-simple/$')
				->setBranch(Themes::BACK_END)
				->setTemplate('init');
		}
		
		Router::getAction($this, 'actionEdit')->setTemplate('dbcheck');
	}


}

?>