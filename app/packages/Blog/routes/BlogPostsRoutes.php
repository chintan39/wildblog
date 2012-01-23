<?php
/*
    Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


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