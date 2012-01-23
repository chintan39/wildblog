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


class BasicTagsController extends AbstractNodesController {
	
	public function actionArticlesTagDetail($args) {
		
		// post tag detail processing
		$tag = $args;

		$items = new ItemCollection("articles", $this, null, "tagPosts");
		$items->setLimit(10);
		$items->setDm($tag);
		$items->setLoadDataModelName('BasicArticlesModel');
		$items->loadCollection();
		$items->addLinks(Environment::getPackage("Basic")->getController("Articles"), "actionDetail");
		Environment::getPackage("Basic")->getController("Articles")->addPostTagsComments($items);

		$tag->addNonDbProperty("articles");
		$tag->articles = $items;
		
		// assign to template
		$this->assign("articles", $tag->articles);
		$this->assign("title", tg("Articles with tag") . ' ' . $tag->title);
	}

	
	public function actionNewsTagDetail($args) {
		
		// post tag detail processing
		$tag = $args;

		$items = new ItemCollection("news", $this, null, "tagNews");
		$items->setLimit(10);
		$items->setDm($tag);
		$items->setLoadDataModelName('BasicNewsModel');
		$items->loadCollection();
		$items->addLinks(Environment::getPackage("Basic")->getController("News"), "actionDetail");

		if ($items->data["items"]) {
			foreach ($items->data["items"] as $key => $item) {
				$item->addNonDbProperty("preview");
				$item->preview = $item->getPreview();
			}
		}
		
		$tag->addNonDbProperty("news");
		$tag->news = $items;
		
		// assign to template
		$this->assign("news", $tag->news);
		$this->assign("title", tg("News with tag") . ' ' . $tag->title);
	}

	
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(null, array('actionNewsTagDetail' => tg('News'), 'actionArticlesTagDetail' => tg('Articles')));
	}
	
}

?>
