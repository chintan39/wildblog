<?php

class BlogPostsController extends AbstractPagesController {
	
	/**
	 * Posts List action
	 */
	public function actionPostsList($args) {
		$items = new ItemCollection("blogposts", $this);
		$items->setPagingAjax(true);
		$items->setSorting(array(new ItemSorting("published", SORTING_DESC)));
		$items->setLimit(6);
		$items->loadCollection();
		$items->addLinks(null, "actionDetail");
		$this->addPostTagsComments($items);
		$this->assign("title", "");
		$this->assign("pageTitle", tp("Project Title"));
		$this->assign($items->getIdentifier(), $items);
	}
	
	public function addPostTagsComments(&$items) {
		if ($items->data["items"]) {
			foreach ($items->data["items"] as $key => $item) {
				$item->addNonDbProperty("tags");
				$item->tags = $item->Find("BlogTagsModel");
				$item->addNonDbProperty("commentsCount");
				$item->commentsCount = BlogCommentsModel::SearchCount("BlogCommentsModel", array("post = ?", "active = ?"), array($item->id, 1));
			}
		}
	}
	

	/**
	 * RSS action
	 */
	public function actionRss($args) {
		$items = new ItemCollection("blogposts", $this);
		$items->setSorting(array(new ItemSorting("published", SORTING_DESC)));
		$items->loadCollection();
		$items->addLinks(null, "actionDetail");
		$this->assign('items', $items);
		$rssInfo = array(
			'publishDate' => date('Y-m-d H:00:00'),
			'link' => Request::getRequestActionLink(),
			);
		$this->assign('rssInfo', $rssInfo);
		Request::setMimeType("application/rss+xml");
		//$this->display('rss');
	}

	public function actionDetail($args) {

		// navigation
		$home = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, 'actionPostsList'), 
			'label' => tp('Homepage'), 
			'title' => tp('Homepage')));
		$blog = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, 'actionPostsList'), 
			'label' => tp('Blog'), 
			'title' => tp('Blog')));
		$navigation = new LinkCollection();
		$navigation->addLink($home);
		$navigation->addLink($blog);
		$this->assign("navigation", $navigation->getLinks());
		
		// post detail processing
		// tags
		$post = $args;
		$post->addNonDbProperty("tags");
		$post->tags = $post->Find("BlogTagsModel");

		// related posts
		$related = new ItemCollection("relatedPosts", $this, null, "getRelatedPosts");
		$postsIds = array($post->id);
		$related->loadCollection($postsIds);
		$related->addLinks(null, "actionDetail");
		$this->assign("relatedPosts", $related);
		
		// comments
		$post->addNonDbProperty("comments");
		$commentsModel = new BlogCommentsModel();
		$post->comments = $commentsModel->Find("BlogCommentsModel", array("post = ?", "active = ?"), array($post->id, 1));
		if ($post->comments) {
			foreach ($post->comments as $key => $comment) {
				$comment->databaseValues['text'] = Utilities::parseBBCode($comment->text);
			}
		}
		
		
		// assign to template
		$this->assign("title", $post->title);
		$this->assign("pageTitle", $post->title . ' | ' . tp("Project Title Short"));
		$this->assign("post", $post);
		
		// handel new comment form
		$comment = new BlogCommentsModel();
		$form = new Form();
		$form->useRecaptcha(true);
		$form->setIdentifier("commentNewForm");
		$form->addPredefinedValue("post", $post->id);
		$form->addPredefinedValue("parent", 0);
		$form->addPredefinedValue("active", 0);
		$form->addPredefinedValue("author", 0);
		$form->addPredefinedValue("description", "");
		$form->addPredefinedValue("seo_description", "");
		$form->addPredefinedValue("seo_keywords", "");
		$form->fill($comment);
		$form->setLabel(tg("Add New Comment"));
		$form->setDescription(tg("Note: Comments will be visible after manual check by admin."));
		// handeling the form request
		$form->handleRequest(array('all' => array(
			'package' => $this->package, 
			'controller' => $this, 
			'action' => 'actionDetail',
			'item' => $post)));
		$this->assign($form->getIdentifier(), $form->toArray());

	}
	
	
	/**
	 * Archive action
	 */
	public function actionBlogPostArchivMonth($args) {
		$items = new ItemCollection("blogposts", $this);
		$items->setPagingAjax(true);
		$filters = array(new ItemQualification("YEAR(published) = ?", $args["year"]));
    	if (array_key_exists("month", $args)) {
    		$filters[] = new ItemQualification("MONTH(published) = ?", $args["month"]);
    	}
		$items->setQualification(array("archive" => $filters));
		$items->setSorting(array(new ItemSorting("published", SORTING_DESC)));
		$items->setLimit(6);
		$items->loadCollection();
		$items->addLinks(null, "actionDetail");
		$this->addPostTagsComments($items);
		$this->assign($items->getIdentifier(), $items);
		$year_month = $args['year'] . (isset($args['month']) ? '/' . $args['month'] : '');
		$this->assign("title", "Blog archive $year_month");
		$this->assign("pageTitle", "Blog archive $year_month" . ' | ' . tp("Project Title Short"));
	}
	
	
	/**
	 * Archive action
	 */
	public function actionBlogPostArchivYear($args) {
		$this->actionBlogPostArchivMonth($args);
	}

	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionPostsList' => tg('List of posts')), array('actionDetail' => tg('Blog post')));
	}
	
	public function subactionRecentPosts() {
		Benchmark::log("Begin of creating PostsController::subactionRecentPosts");

		$recentPosts = $this->loadCache('recentPosts');
		if (!$recentPosts) {
			$recentPosts = new ItemCollection("recentPosts", $this);
			$recentPosts->setLinks("actionDetail");
			$recentPosts->setSorting(array(new ItemSorting("published", SORTING_DESC)));
			$recentPosts->setLimit(4);
			$recentPosts->loadCollection();
			$recentPosts->addLinks();
			$this->saveCache('recentPosts', $recentPosts, array('BlogPostsModel'));
		}
		$this->assign($recentPosts->getIdentifier(), $recentPosts);

		Benchmark::log("End of creating PostsController::subactionRecentPosts");
	}
	
	/** 
	 * Searching method returns all items that should be found.
	 * @return array of object
	 */
	public function getSearchItems($text) {
		$searchPosts = new ItemCollection("searchPosts", $this);
		$searchPosts->setLimit(10);
		$searchPosts->setLinks("actionDetail");
		$searchPosts->setQualification(array("fulltext" => array(new ItemQualification("title LIKE ? OR text LIKE ?", array('%' . $text . '%', '%' . $text . '%')))));
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

	public function getRSSFeed() {
		return array(array(
			"name" => tp("Project Title") . " - " . tp("Blog RSS Feed"),
			"link" => Request::getLinkSimple($this->package, $this->name, 'actionRss')));
	}
	
}

?>