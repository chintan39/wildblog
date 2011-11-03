<?php

class BaseDictionaryRoutes extends AbstractDefaultRoutes {

	public $order = 2;				// order of the Routes (0-10)
	
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {
		
		AbstractAdminRoutes::setRouter($this, Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
		Router::registerAction($this, 'actionAnalyze')
			->addRuleUrl('admin/dictionary/analyze/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('index')
			->setPermission(Permission::$ADMIN);
		
		Router::registerAction($this, 'actionAnalyzeRemove')
			->addRuleUrl('admin/dictionary/analyze-remove/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('index')
			->setPermission(Permission::$ADMIN);
		
		Router::registerAction($this, 'actionAnalyzeAdd')
			->addRuleUrl('admin/dictionary/analyze-add/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('index')
			->setPermission(Permission::$ADMIN);
		
	}
	

	
}

?>