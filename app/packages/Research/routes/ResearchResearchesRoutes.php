<?php

class ResearchResearchesRoutes extends AbstractPagesRoutes {
	
	public $order = 2;				// order of the Routes (0-10 asc)

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

		Router::registerAction($this, 'actionDetail')
			->addRuleUrl('research/[url]/$')
			->setTemplate('researchDetail');
		
		$adminActionsPrefix = isset($this->actionsPrefix) ? $this->actionsPrefix : 'admin/' . strtolower($this->package) . '/' . strtolower($this->name) . '/';

		Router::registerAction($this, 'actionViewResults') 
			->addRuleUrl($adminActionsPrefix . 'view-results/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultList')
			->setPermission(Permission::$ADMIN);
		
		Router::registerAction($this, 'actionViewResultsCSV')
			->addRuleUrl($adminActionsPrefix . 'view-results-csv/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultListCsv')
			->setPermission(Permission::$ADMIN);
		
	}

	
}

?>