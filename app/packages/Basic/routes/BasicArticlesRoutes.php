<?php

class BasicArticlesRoutes extends AbstractPagesRoutes {
	
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
			->addRuleUrl('art/[url]/$')
			->setTemplate('articleDetail');

		Router::registerAction($this, 'actionHomepageArticle')
			->addRuleUrl('homepage-article/$')
			->setTemplate('articleDetail');

		Router::registerSubaction($this, 'subactionPersonalInfo')
			->setTemplate('part.personalInfo');

		Router::registerSubaction($this, 'subactionArticlesTree')
			->setTemplate('part.articlesMenu');

		Router::registerSubaction($this, 'subactionHomepageArticle')
			->setTemplate('part.homepageArticle');

		Router::registerSubaction($this, 'subactionContactArticle') 
			->setTemplate('part.shortContact');

		Router::registerSubaction($this, 'subactionFooterArticle')
			->setTemplate('part.footerArticle');

		Router::registerSubaction($this, 'subactionTagsMenu')
			->setTemplate('part.tagsMenu');
	}

	
}

?>