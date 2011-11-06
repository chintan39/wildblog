<?php

class BlogPostsRoutes extends AbstractPagesRoutes {
	
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

		Router::registerAction($this, 'actionPostsList')
			->addRuleUrl('blog/$')
			->setTemplate('blogList');

		Router::registerAction($this, 'actionRss')
			->addRuleUrl('blog-feed/$')
			->setTemplate('rss');

		Router::registerAction($this, 'actionDetail')
			->addRuleUrl('blog/[url]/$')
			->setTemplate('blogPostDetail');

		Router::registerAction($this, 'actionBlogPostArchivMonth')
			->addRuleUrl('archive/#^($year=[0-9]{4})$#/#^($month=[0-9]{2})$#/')
			->setTemplate('blogList');

		Router::registerAction($this, 'actionBlogPostArchivYear')
			->addRuleUrl('archive/#^($year=[0-9]{4})$#/$')
			->setTemplate('blogList');

		Router::registerSubaction($this, 'subactionRecentPosts')
			->addRuleUrl('#^(?!admin)(.*)$#')
			->setTemplate('part.recentPosts');

	}

	/**
	 * Overloaded method to check if archive values (year and month) are good.
	 */
	public function checkRequestCondition($filters, $values) {
		// check which rule is used
		if (in_array("year", $filters)) {
			$cond = true;
			$output = array_combine($filters, $values);
	
			// we need to keep values as strings to reconstruct the url
			if (array_key_exists("month", $output)) {
				$cond &= ((int)$output["month"] <= 12 && (int)$output["month"] >= 1);
			}
			if (array_key_exists("year", $output)) {
				$cond &= ((int)$output["year"] <= 2100 && (int)$output["year"] >= 1900);
			}
			
			return ($cond ? array($output) : false);
		} else {
			return parent::checkRequestCondition($filters, $values);
		}
	}

	
}

?>