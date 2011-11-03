<?php

class BasicContactFormRoutes extends AbstractPagesRoutes {
	
	public $order = 4;				// order of the Routes (0-10 asc)

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

		Router::registerSubaction($this, 'subactionContactForm')
			->setTemplate('part.contactForm');

		Router::registerAction($this, 'actionContactForm')
			->addRuleUrl('contact-form')
			->setTemplate('contactForm');

	}
	
}

?>