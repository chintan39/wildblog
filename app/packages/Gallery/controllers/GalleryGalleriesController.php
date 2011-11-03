<?php

class GalleryGalleriesController extends AbstractPagesController {
	
	/**
	 * Galleries list action
	 */
	public function actionGalleriesList($args) {
		$items = new ItemCollection("galleriesList", $this);
		$items->setSorting(array(array("column" => "published", "direction" => "DESC")));
		$items->loadCollection();
		$items->addLinks(null, "actionGalleryDetail");
		if ($items->data["items"]) {
			foreach ($items->data["items"] as $key => $item) {
				$item->addNonDbProperty("titleimage");
				$item->titleimage = $item->FindTitleImage("GalleryImagesModel", "GalleryGalleriesImagesModel");
			}
		}

		$this->assign("title", tp("Galleries list"));
		$this->assign($items->getIdentifier(), $items);
	}
	
	
	/**
	 * Galleries list action
	 */
	public function subactionGalleriesList($args) {
		$items = new ItemCollection("latestGalleriesList", $this);
		$items->setSorting(array(array("column" => "published", "direction" => "DESC")));
		$items->setLimit(Config::Get("GALLERY_GALLERIES_SITE_COUNT"));
		$items->loadCollection();
		$items->addLinks(null, "actionGalleryDetail");
		if ($items->data["items"]) {
			foreach ($items->data["items"] as $key => $item) {
				$item->addNonDbProperty("titleimage");
				$item->titleimage = $item->FindTitleImage("GalleryImagesModel", "GalleryGalleriesImagesModel");
			}
		}

		$this->assign("title", tp("Galleries list"));
		$this->assign($items->getIdentifier(), $items);
	}


	/**
	 * Gallery detail
	 */
	public function actionGalleryDetail($args) {

		// gallery detail processing
		$gallery = $args;
		$gallery->addNonDbProperty("titleimage");
		$gallery->titleimage = $gallery->FindTitleImage("GalleryImagesModel", "GalleryGalleriesImagesModel");
		$gallery->addNonDbProperty("images");
		$images = new ItemCollection("galleriesList", Environment::getPackage($this->getPackage())->getController('Images'), 'GalleryImagesModel', 'getCollectionItemsFromItems');
		$images->loadItemsFromItems($gallery->Find('GalleryImagesModel'));
		$gallery->images = $images;

		// assign to template
		$this->assign("title", $gallery->title);
		$this->assign("pageTitle", $gallery->title . ' | ' . tp("Project Title Short"));
		$this->assign("gallery", $gallery);
	}
	

	/**
	 * Returns all galleries, that should be in Sitemap.
	 */
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionGalleriesList' => tg('Galleries list')), array('actionGalleryDetail' => tg('Gallery')));
	}	
}

?>
