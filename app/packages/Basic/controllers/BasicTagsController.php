<?php

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
