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


class GalleryGalleriesController extends AbstractPagesController {
	
	/**
	 * Galleries list action
	 */
	public function actionGalleriesList($args) {
		$items = new ItemCollection("galleriesList", $this);
		$items->setSorting(array(new ItemSorting("published", SORTING_DESC)));
		$items->loadCollection();
		$items->addLinks(null, "actionGalleryDetail");
		if ($items->data["items"]) {
			foreach ($items->data["items"] as $key => $item) {
				$item->addNonDbProperty("titleimage");
				$item->titleimage = $item->FindTitleImage("GalleryImagesModel", "GalleryGalleriesImagesModel");
				$item->addNonDbProperty("firstimages");
				if (Config::Get('GALLERY_GALLERIES_LIST_IMAGES_COUNT') && ($firstImagesLimit = (Config::Get('GALLERY_GALLERIES_LIST_IMAGES_COUNT') - 1)) > 0) {
					$images = new ItemCollection("galleriesList", Environment::getPackage($this->getPackage())->getController('Images'));
					$images->setLimit($firstImagesLimit);
					// we don't want same images as titleimage
					$f = array();
					$v = array();
					if ($item->titleimage) {
						$f['GalleryGalleriesModel'] = array("id = ?");
						$v[] = $item->id;
						$f[] = 'image <> ?';
						$v[] = $item->titleimage;
					}
					$images->loadItemsFromItems(GalleryImagesModel::Search('GalleryImagesModel', $f, $v, array("LIMIT 0, $firstImagesLimit")));
					$item->firstimages = $images;
				} else {
					$item->firstimages = false;
				}
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
		$items->setSorting(array(new ItemSorting("published", SORTING_DESC)));
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
		$images = new ItemCollection("galleriesList", Environment::getPackage($this->getPackage())->getController('Images'));
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
