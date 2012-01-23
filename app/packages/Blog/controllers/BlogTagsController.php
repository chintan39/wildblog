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