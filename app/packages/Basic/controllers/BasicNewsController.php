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


class BasicNewsController extends AbstractPagesController {
	
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

	/**
	 * News List action
	 */
	public function actionNewsList($args) {
		$items = new ItemCollection("news", $this);
		$items->setLimit(Config::Get('BASIC_NEWS_LIMIT'));
		$items->loadCollection();
		$items->addLinks(null, "actionDetail");
		if ($items->data["items"]) {
			foreach ($items->data["items"] as $key => $item) {
				$item->addNonDbProperty("preview");
				$item->preview = $item->getPreview();
			}
		}
		$this->assign("title", tg("News"));
		$this->assign("pageTitle", tp("Project Title"));
		$this->assign($items->getIdentifier(), $items);
	}
	

	/**
	 * RSS action
	 */
	public function actionRss($args) {
		$items = new ItemCollection("news", $this);
		$items->loadCollection();
		$items->addLinks(null, "actionDetail");
		$this->assign('items', $items);
		$rssInfo = array(
			'publishDate' => date('Y-m-d H:00:00'),
			'link' => Request::getRequestActionLink(),
			);
		$this->assign('rssInfo', $rssInfo);
		Request::setMimeType("application/rss+xml");
	}

	public function actionDetail($args) {

		// navigation
		$news = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, 'actionNewsList'), 
			'label' => tp('News'), 
			'title' => tp('News')));
		$navigation = new LinkCollection();
		$navigation->addLink($news);
		$this->assign("navigation", $navigation->getLinks());
		
		$news = $args;

		// assign to template
		$this->assign("title", $news->title);
		$this->assign("pageTitle", $news->title . ' | ' . tp("Project Title Short"));
		$this->assign("news", $news);
		
	}
	
	
	/**
	 * Archive action
	 */
	public function actionNewsArchivMonth($args) {
		$items = new ItemCollection("news", $this);
		$filters = array();
		$filters[] = new ItemQualification("YEAR(published) = ?", $args["year"]);
    	if (array_key_exists("month", $args)) {
    		$filters[] = new ItemQualification("MONTH(published) = ?", $args["month"]);
    	}
		$items->addQualification(array("archive" => $filters));
		$items->setLimit(Config::Get('BASIC_NEWS_LIMIT'));
		$items->loadCollection();
		$items->addLinks(null, "actionDetail");
		$this->assign($items->getIdentifier(), $items);
		$year_month = $args['year'] . (isset($args['month']) ? '/' . $args['month'] : '');
		$this->assign("title", tg("Blog archive") . ' ' . $year_month);
		$this->assign("pageTitle", tg("Blog archive") . ' ' . $year_month . ' | ' . tp("Project Title Short"));
	}
	
	
	/**
	 * Archive action
	 */
	public function actionNewsArchivYear($args) {
		$this->actionNewsArchivMonth($args);
	}
	
	
	public function actionNewsPrimaryTag($args) {
		if (Config::Get('BASIC_NEWS_PRIMARY_TAG')) {
			$tag = new BasicTagsModel(Config::Get('BASIC_NEWS_PRIMARY_TAG'));
			Environment::getPackage($this->package)->getController('Tags')->actionNewsTagDetail($tag);
		} else {
			$this->actionNewsList(null);
		}
	}
	

	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionNewsList' => tg('List of news')), array('actionDetail' => tg('News')));
	}
	
	
	public function subactionRecentNews() {
		Benchmark::log("Begin of creating NewsController::subactionRecentNews");

		$recentNews = $this->loadCache('recentNews');
		if (!$recentNews) {
			$recentNews = new ItemCollection("recentNews", $this);
			$recentNews->setLinks("actionDetail");
			$recentNews->setLimit(Config::Get('BASIC_NEWS_RECENT_COUNT'));
			$recentNews->loadCollection();
			$recentNews->addLinks();
			$this->saveCache('recentNews', $recentNews, array('BasicNewsModel'));
		}
		$this->assign($recentNews->getIdentifier(), $recentNews);

		Benchmark::log("End of creating NewsController::subactionRecentNews");
	}
	
	/** 
	 * Searching method returns all items that should be found.
	 * @return array of object
	 */
	public function getSearchItems($text) {
		$searchNews = new ItemCollection("searchNews", $this);
		$searchNews->setLimit(Config::Get('BASIC_NEWS_LIMIT'));
		$searchNews->setLinks("actionDetail");
		$searchNews->addQualification(array("fulltext" => array(new ItemQualification("title LIKE ? OR text LIKE ?", array('%' . $text . '%', '%' . $text . '%')))));
		$searchNews->loadCollection();
		$searchNews->addLinks();
		if ($searchNews->data["items"]) {
			foreach ($searchNews->data["items"] as $key => $item) {
				$item->addNonDbProperty("preview");
				$item->preview = trim($item->description) ? $item->description : Utilities::truncate(strip_tags($item->text), 250);
			}
		}
		return $searchNews;
	}

	
	public function getRSSFeed() {
		return array(array(
			"name" => tp("Project Title") . " - " . tp("Blog RSS Feed"),
			"link" => Request::getLinkSimple($this->package, $this->name, 'actionRss')));
	}
	
}

?>