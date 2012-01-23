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


class BasicArticlesController extends AbstractPagesController {
	
	public $order = 2;				// order of the controller (0-10 asc)
	var $detailMethodName = 'actionDetail';


	/**
	 * Request handler
	 * List of items will be stored in ItemCollection object, then data from the collection 
	 * will be printed with specified buttons, paging, etc.
	 */
	public function actionListing($args) {

		$items = new ItemCollectionTree($this->getMainListIdentifier(), $this);
		$items->setPagingAjax(true);
		$items->setQualification(null); // we overload filters - no qualifications are used
		$items->setDefaultFilters();
		$items->handleFilters();
		$items->forceLanguage(Language::get(Themes::FRONT_END));
		$items->treeBase(ItemCollectionTree::treeRoot);
		$items->treePull(ItemCollectionTree::treeAncestors | ItemCollectionTree::treeDescendants);
		$items->loadCollection();

		$buttons = $this->getListingButtons();
		
		$items->addButtons($buttons);
		
		$this->assign($items->getIdentifier(), $items);
		$this->assign('title', tg('List of ' . strtolower($this->name)));
		$this->assign('help', tg($this->description));
		
		// Top menu
		$this->addTopMenu();
	}

	/**
	 * Request handler
	 * Personal Info on all pages. 
	 */
	public function subactionPersonalInfo($args) {
		Benchmark::log("Begin of creating ArticlesController::subactionPersonalInfo");
		$personalInfoArticle = $this->loadCache('personalInfoArticle');
		if (!$personalInfoArticle) {
			if (Config::Get("BASIC_PERSONAL_INFO_ARTICLE_ID")) { 
				$personalInfoArticle = new BasicArticlesModel(Config::Get("BASIC_PERSONAL_INFO_ARTICLE_ID"));
			} else {
				$personalInfoArticle = false;
			}
			$this->saveCache('personalInfoArticle', $personalInfoArticle, array('BasicArticlesModel', 'BaseConfigModel'));
		}
		$this->assign("personalInfo", $personalInfoArticle);
		Benchmark::log("End of creating ArticlesController::subactionPersonalInfo");
	}

	/**
	 * Request handler
	 * Short Contact Info on all pages. 
	 */
	public function subactionContactArticle($args) {
		Benchmark::log("Begin of creating ArticlesController::subactionContactArticle");
		$shortContactArticle = $this->loadCache('shortContactArticle');
		if (!$shortContactArticle) {
			if (Config::Get("BASIC_SHORT_CONTACT_ARTICLE_ID")) { 
				$shortContactArticle = new BasicArticlesModel(Config::Get("BASIC_SHORT_CONTACT_ARTICLE_ID"));
			} else {
				$shortContactArticle = false;
			}
			$this->saveCache('shortContactArticle', $shortContactArticle, array('BasicArticlesModel', 'BaseConfigModel'));
		}
		$this->assign("shortContact", $shortContactArticle);
		Benchmark::log("End of creating ArticlesController::subactionContactArticle");
	}
	
	/**
	 * Request handler
	 * Footer on all pages. 
	 */
	public function subactionFooterArticle($args) {
		Benchmark::log("Begin of creating ArticlesController::subactionFooterArticle");
		$footerArticle = $this->loadCache('footerArticle');
		if (!$footerArticle) {
			if (Config::Get('BASIC_FOOTER_ARTICLE_ID')) { 
				$footerArticle = new BasicArticlesModel(Config::Get('BASIC_FOOTER_ARTICLE_ID'));
			} else {
				$footerArticle = false;
			}
			$this->saveCache('footerArticle', $footerArticle, array('BasicArticlesModel', 'BaseConfigModel'));
		}
		$this->assign('footerArticle', $footerArticle);
		Benchmark::log("End of creating ArticlesController::subactionFooterArticle");
	}
	
	/**
	 * Request handler
	 * Articles structure generation. 
	 */
	public function subactionArticlesTree($args) {
		Benchmark::log("Begin of creating ArticlesController::subactionArticlesTree");
		$articlesTree = $this->loadCache('articlesTree');
		if (!$articlesTree) {
			$articlesTree = new LinkCollection();
			$this->linksTree($articlesTree->links, 0);
			$articlesTree->markAllLinks();
			$this->saveCache('articlesTree', $articlesTree, array('BasicArticlesModel'));
		}
		
		$this->assign('articlesTree', $articlesTree);
		Benchmark::log("End of creating ArticlesController::subactionArticlesTree");
	}

	
	public function linksTree(&$result, $parent) {
		$tmp = new BasicArticlesModel();
		$tmpItems = $tmp->getItems('BasicArticlesModel', array('parent = ?'), array($parent));
		if ($tmpItems) {
			foreach ($tmpItems as $i) {
				$linkAddress = Request::getLinkItem($this->package, $this->name, 'actionDetail', $i);
				$action = array(
					'package' => $this->package,
					'controller' => $this->name,
					'action' => 'actionDetail',
					'item' => $i->id,
					);
				$link = new Link(array(
				'link' => $linkAddress,
				'label' => '(' . $this->name . ') ' . $i->title, 
				'title' => $i->title,
				'action' => $action,
				));
				$this->linksTree($link->subLinks, $i->id);
				if (Config::Get('BASIC_ARTICLES_ADD_ANCHORS_INTO_SITEMENU')) {
					$this->addAnchors($link->subLinks, $i->text, $linkAddress, $action);
				}
				$result[] = $link;
			}
		}
	}
	
	
	private function addAnchors(&$result, $text, $parentLink, $action) {
		if (preg_match_all('/a\s+name="([^"]+)"/i', $text, $matches)) {
			foreach ($matches[1] as $anchor) {
				$link = new Link(array(
				'link' => $parentLink . '#' . $anchor, 
				'label' => $anchor, 
				'title' => $anchor,
				'action' => $action,
				));
				$result[] = $link;
			}
		}
	}
	

	/**
	 * Request handler
	 * Homepage article generation. 
	 */
	public function subactionHomepageArticle($args) {
		if (Request::isHomepage()) {
			Benchmark::log("Begin of creating ArticlesController::subactionHomepageArticle");
			$hpArticle = BasicArticlesModel::Search('BasicArticlesModel', array('url = ?'), array('homepage-article'));
			if ($hpArticle) {
				$hpArticle = $hpArticle[0];
			}
			$this->assign('homepageArticle', $hpArticle);
			Benchmark::log("End of creating ArticlesController::subactionHomepageArticle");
		}
	}
		
	public function actionDetail($args) {
		// article detail processing
		$article = $args;
		if ($article->seo_description) {
			$this->assign('seoDescription', $article->seo_description);
		}
		if ($article->seo_keywords) {
			$this->assign('seoKeywords', $article->seo_keywords);
		}
		
		$article->addNonDbProperty('hasContactForm');
		$article->hasContactForm = (Config::Get('BASIC_ARTICLES_CONTACT_FORM')
			&& in_array($article->id, explode(':', Config::Get('BASIC_ARTICLES_CONTACT_FORM'))));

		$this->assign('title', $article->title);
		$this->assign('pageTitle', $article->title . ' | ' . tp('Project Title Short'));
		$this->assign('article', $article);
	}
	
	
	public function actionHomepageArticle($args) {
		Benchmark::log('Begin of creating ArticlesController::actionHomepageArticle');
		// article detail processing
		$homepageArticle = $this->loadCache('homepageArticle');
		if (!$homepageArticle) {
			if (Config::Get('BASIC_HOMEPAGE_ARTICLE_ID')) { 
				$homepageArticle = new BasicArticlesModel(Config::Get('BASIC_HOMEPAGE_ARTICLE_ID'));
			} else {
				$homepageArticle = false;
			}
			$this->saveCache('homepageArticle', $homepageArticle, array('BasicArticlesModel', 'BaseConfigModel'));
		}
		if ($homepageArticle) {
			$this->assign('title', $homepageArticle->title);
		}
		$this->assign('pageTitle', tp('Project Title'));
		$this->assign('article', $homepageArticle);
		Benchmark::log('End of creating ArticlesController::actionHomepageArticle');
	}
	
	
	/**
	 * Returns all articles, that should be in Sitemap.
	 */
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionHomepageArticle' => tg('Homepage article')), array('actionDetail' => tg('Article')));
	}

		
	/** 
	 * Searching method returns all items that should be found.
	 * @return array of object
	 */
	public function getSearchItems($text) {
		$searchPosts = new ItemCollection("searchArticles", $this);
		$searchPosts->setLimit(10);
		$searchPosts->setLinks("actionDetail");
		$searchPosts->addQualification(array("fulltext" => array(new ItemQualification("title LIKE ? OR text LIKE ?", array('%' . $text . '%', '%' . $text . '%')))));
		$searchPosts->loadCollection();
		$searchPosts->addLinks();
		if ($searchPosts->data["items"]) {
			foreach ($searchPosts->data["items"] as $key => $item) {
				$item->addNonDbProperty("preview");
				$item->preview = Utilities::truncate(strip_tags($item->text), 250);
			}
		}

		return $searchPosts;
	}

	public function subactionTagsMenu() {
		Benchmark::log("Begin of creating TagsController::subactionTagsMenu");
		$homepageArticleInUse = Request::checkHomepageAction($this->package, $this->name, 'actionHomepageArticle');
		$tagsMenu = $this->loadCache('tagsMenu');
		if (!$tagsMenu) {
			$tagsMenu = array();
			$tags = BasicTagsModel::Search('BasicTagsModel');
			if ($tags) {
				foreach ($tags as $tag) {
					$articles = $tag->Find('BasicArticlesModel');
					if ($articles) {
						foreach ($articles as $key => $art) {
							$articles[$key]->addNonDbProperty('link');
							if ($homepageArticleInUse && $articles[$key] == Config::Get('BASIC_HOMEPAGE_ARTICLE_ID')) {
								$articles[$key]->link = Request::getLinkHomePage();
							} else {
								$articles[$key]->link = Request::getLinkItem($this->package, $this->name, "actionDetail", $art);
							}
						}
					}
					$tagsMenu[$tag->url] = array('tag'=> $tag->removeNeedlessParts(), 'articles' => $articles);
				}
			}
			
			$this->saveCache('tagsMenu', $tagsMenu, array('BasicArticlesModel', 'BasicTagsModel'));
		}
		$this->assign('articlesTags', $tagsMenu);

		Benchmark::log("End of creating TagsController::subactionTagsMenu");
	}

	public function getItemsLinks() {
		return $this->getItemsLinksDefault(array('actionHomepageArticle' => tg('Homepage article')), array('actionDetail' => tg('Article detail')));
	}
	
}

?>