<?php

class ReferencesReferencesRoutes extends AbstractPagesRoutes {
	
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
		
		Router::registerAction($this, 'actionReferencesList')
			->addRuleUrl('references/$')
			->setTemplate('references');
		
		Router::registerAction($this, 'actionReferenceAdd')
			->addRuleUrl('references/add/$')
			->setTemplate('referenceAdd');
		
		Router::registerSubaction($this, 'subactionReferencesList') 
			->setTemplate('part.references');
	}
	

}

?>