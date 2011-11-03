<?php

class BasicMenuRoutes extends AbstractPagesRoutes {
	
	public $order = 2;				// order of the Routes (0-10 asc)
	
	static public $linksList = null;

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

		Router::registerAction($this, 'actionLinksList')
			->addRuleUrl('linkslist/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('index')
			->setPermission(Permission::$ADMIN);

		Router::registerSubaction($this, 'subactionGetMenus')
			->setTemplate('part.allPagesMenus');
	}



}

?>