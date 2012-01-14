<?php

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