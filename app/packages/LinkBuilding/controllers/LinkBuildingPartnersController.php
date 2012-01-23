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


class LinkBuildingPartnersController extends AbstractNodesController {
	
	public function subactionPartnersMenu() {
		Benchmark::log("Begin of creating PartnersController::subactionPartnersMenu");

		$partnersMenu = $this->loadCache('partnersMenu');
		if (!$partnersMenu) {
			$partnersMenu = new ItemCollection("partnersMenu", $this);
			$partnersMenu->setQualification(array("partners" => array(new ItemQualification("all_pages = ?", 1))));
			$partnersMenu->loadCollection();
			$this->saveCache('partnersMenu', $partnersMenu, array('LinkBuildingPartnersModel'));
		}
		$this->assign($partnersMenu->getIdentifier(), $partnersMenu);
		
		Benchmark::log("End of creating PartnersController::subactionPartnersMenu");
	}

	public function actionPartners($args) {
		$items = new ItemCollection("partners", $this);
		$items->setLimit(0);
		$items->loadCollection();

		// assign to template
		$this->assign($items->getIdentifier(), $items);
		$this->assign("title", tg("Partners"));
		
		// show template
		//$this->display("partners");
	}
	
	
	/**
	 * Returns all articles, that should be in Sitemap.
	 */
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionPartners' => tg('Partners list')), array());
	}
	
}

?>