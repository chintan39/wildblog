<?php

class NewsletterMessagesRoutes extends AbstractPagesRoutes {
	
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

		Router::registerAction($this, 'actionCheckSending') 
			->addRuleUrl('admin/newsletter/messages/check/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('check')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);

		Router::registerAction($this, 'actionSend')
			->addRuleUrl('admin/newsletter/messages/send/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('send')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);

	}
	
}

?>