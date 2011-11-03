<?php

class BasicNewsRoutes extends AbstractPagesRoutes {
	
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

		Router::registerAction($this, 'actionNewsList')
			->addRuleUrl('news/$')
			->setTemplate('newsList');

		Router::registerAction($this, 'actionRss')
			->addRuleUrl('news-feed/$')
			->setTemplate('Base|rss');

		Router::registerAction($this, 'actionDetail')
			->addRuleUrl('news/[url]/$')
			->setTemplate('newsDetail');

		Router::registerAction($this, 'actionNewsArchivMonth')
			->addRuleUrl('news-archive/#^($year=[0-9]{4})$#/#^($month=[0-9]{2})$#/')
			->setTemplate('newsList');

		Router::registerAction($this, 'actionNewsArchivYear')
			->addRuleUrl('news-archive/#^($year=[0-9]{4})$#/$')
			->setTemplate('newsList');

		Router::registerAction($this, 'actionNewsPrimaryTag')
			->addRuleUrl('news-primary-tag/$')
			->setTemplate('newsList');
		
		Router::registerSubaction($this, 'subactionRecentNews')
			->setTemplate('part.recentNews');

	}
	
	
}

?>