<?php

class BlogTagsController extends AbstractNodesController {
	
	public function subactionTagsMenu() {
		Benchmark::log("Begin of creating BlogTagsController::subactionTagsMenu");

		$tagsMenu = $this->loadCache('tagsMenu');
		if (!$tagsMenu) {
			$tagsMenu = new ItemCollection("tagsMenu", $this);
			$tagsMenu->setLinks("actionBlogTagDetail");
			$tagsMenu->loadCollection();
			$tagsMenu->addLinks();
			if ($tagsMenu->data["items"]) {
				foreach ($tagsMenu->data["items"] as $key => $item) {
					$item->addNonDbProperty("postsCount");
					$item->postsCount = $item->FindCount("BlogPostsModel");
					$item->postsCount = ($item->postsCount) ? $item->postsCount : 0;
				}
			}
			$this->saveCache('tagsMenu', $tagsMenu, array('BlogPostsModel', 'BlogTagsModel'));
		}
		$this->assign($tagsMenu->getIdentifier(), $tagsMenu);

		Benchmark::log("End of creating BlogTagsController::subactionTagsMenu");
	}

	public function actionBlogTagDetail($args) {
		
		// post tag detail processing
		$tag = $args;

		$items = new ItemCollection("blogposts", $this);
		$items->setSorting(array(new ItemSorting("published", SORTING_DESC)));
		$items->setLimit(6);
		$items->setDm($tag);
		$items->setLoadDataModelName('BlogPostsModel');
		$items->loadCollection();
		$items->addLinks(Environment::getPackage("Blog")->getController("Posts"), "actionDetail");
		Environment::getPackage("Blog")->getController("Posts")->addPostTagsComments($items);

		$tag->addNonDbProperty("posts");
		$tag->posts = $items;
		
		// assign to template
		$this->assign("blogposts", $tag->posts);
		$this->assign("title", "Posts with tag " . $tag->title);
	}

	
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array(), array('actionBlogTagDetail' => tg('Blog tag')));
	}
	
}

?>