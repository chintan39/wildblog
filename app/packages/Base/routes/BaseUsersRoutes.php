<?php

class BaseUsersRoutes extends AbstractDefaultRoutes {
	
	public $order = 6;				// order of the Routes (0-10)
	
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

		Router::registerAction($this, 'actionLogin')
			->addRuleUrl('admin/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('login');

		Router::registerAction($this, 'actionLogout')
			->addRuleUrl('admin/logout/$')
			->setBranch(Themes::BACK_END);

		Router::registerAction($this, 'actionEditProfile') 
			->addRuleUrl('admin/edit-profile/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('defaultEdit')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);

	}
	
}

?>