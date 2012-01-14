<?php

class LinkBuildingTagsRoutes extends AbstractCodebookRoutes {
	
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

		Router::registerAction($this, 'actionTagDetail')
			->addRuleUrl('partnerstag/[url]/$')
			->setTemplate('partners');

		Router::registerAction($this, 'actionTagsPartnersList')
			->addRuleUrl('tags-partners/$')
			->setTemplate('tagsPartners');

	}
	
}

?>