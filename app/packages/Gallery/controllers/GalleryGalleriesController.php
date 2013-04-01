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


	/**
	 * Displays images of the gallery and allows to upload images in a simple way.
	 */
	public function actionSimpleImages($gallery) {
		Request::reGenerateToken();
		$this->actionEditAdjustItem($item);
		$form = new Form();
		$form->setFocusFirstItem(true);
		$form->setSendAjax(Request::isAjax());
		$form->setUseTabs(true);
		$form->setCsrf(true);
		$form->setIdentifier(strtolower($this->name));

		// new action if specified
		if ($this->newMethodName) {
			$form->setSaveAsAction(Request::getLinkSimple($this->package, $this->name, $this->newMethodName));
		}
		
		$form->fill($item, $this->getEditButtons());
		$form->addAlternativeAction($this->package, $this, 'actionEdit', $item, tg('Edit item form'));
		$form->addAlternativeAction($this->package, $this, 'actionListing', null, tg('Items list'));
		$form->setDescription($this->getFormDescription());
		
		// handeling the form request
		$form->handleRequest($this->getEditActionsAfterHandlin(), tg('Item has been saved.'));
		$this->assign('form', $form->toArray());
		$this->assign('gallery', $gallery);

		$this->assign('title', tg('Edit ' . strtolower($this->name)));
		$this->assign('help', tg($this->description));
		
		// detail action if specified
		if ($this->detailMethodName && !$isSimple) {
			$this->assign('detailLink', Request::getLinkItem($this->package, $this->name, $this->detailMethodName, $item));
		}
		
		// view action if specified
		if ($this->viewMethodName && !$isSimple) {
			$this->assign('viewLink', Request::getLinkItem($this->package, $this->name, $this->viewMethodName, $item));
		}
		
		// detail action if specified
		if ($this->removeMethodName && !$isSimple) {
			$this->assign('removeLink', Request::getLinkItem($this->package, $this->name, $this->removeMethodName, $item, array('token' => Request::$tokenCurrent)));
		}
		
		// detail action if specified
		if ($this->removeSimpleMethodName && $isSimple) {
			$this->assign('removeLinkSimple', Request::getLinkItem($this->package, $this->name, $this->removeSimpleMethodName, $item, array('token' => Request::$tokenCurrent)));
		}
		
		// Top menu
		$this->addTopMenu();
		
		if (Config::Get('EDIT_TIMEOUT_WARNING')) {
			Javascript::addTimeout('Your session will time out soon.', Config::Get('EDIT_TIMEOUT_WARNING'));
		}
	}

}

?>
