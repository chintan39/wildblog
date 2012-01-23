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


class LinkBuildingTagsController extends AbstractCodebookController {
	

	public function actionTagsPartnersList() {
		$tagsPartnersMenu = $this->loadCache('tagsPartnersMenu');
		if (!$tagsPartnersMenu) {
			$tagsPartnersMenu = new ItemCollection("tagsPartnersMenu", $this);
			$tagsPartnersMenu->loadCollection();
			if ($tagsPartnersMenu->data["items"]) {
				foreach ($tagsPartnersMenu->data["items"] as $key => $item) {
					$item->addNonDbProperty("partners");
					$item->partners = $item->Find("LinkBuildingPartnersModel");
				}
			}
			$this->saveCache('tagsPartnersMenu', $tagsPartnersMenu, array('LinkBuildingPartnersModel', 'LinkBuildingTagsModel'));
		}
		$this->assign($tagsPartnersMenu->getIdentifier(), $tagsPartnersMenu);
	}

	public function actionTagDetail($args) {
		
		// post tag detail processing
		$tag = $args;

		$items = new ItemCollection("partners", $this);
		$items->setDm($tag);
		$items->setLoadDataModelName('LinkBuildingPartnersModel');
		$items->loadCollection();
		//$items->addLinks(Environment::getPackage("LinkBuilding")->getController("Partners"), "actionPartners");

		$tag->addNonDbProperty("partners");
		$tag->partners = $items;
		
		// assign to template
		$this->assign("tag", $tag);
	}

}
?>