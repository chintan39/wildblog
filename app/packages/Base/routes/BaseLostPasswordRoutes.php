<?php

class BaseLostPasswordRoutes extends AbstractDefaultRoutes {
	
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

		Router::registerAction($this, 'actionLostPassword')
			->addRuleUrl('admin/lost-password/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('lostPassword');

		Router::registerAction($this, 'actionLostPasswordChange')
			->addRuleUrl('admin/change-lost-password/[token]/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('lostPasswordChange');

		Router::registerAction($this, 'actionLostPasswordChangeDone') 
			->addRuleUrl('admin/change-lost-password-done/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('index');

	}
	
}

?>