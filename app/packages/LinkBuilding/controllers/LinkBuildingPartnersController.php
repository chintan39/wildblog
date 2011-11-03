<?php

class LinkBuildingPartnersController extends AbstractNodesController {
	
	public function subactionPartnersMenu() {
		Benchmark::log("Begin of creating PartnersController::subactionPartnersMenu");

		$partnersMenu = $this->loadCache('partnersMenu');
		if (!$partnersMenu) {
			$partnersMenu = new ItemCollection("partnersMenu", $this);
			$partnersMenu->setQualification(array("partners" => array("all_pages = ?" => 1)));
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