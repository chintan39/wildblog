<?php

class BasicMenuController extends AbstractPagesController {
	
	public $order = 2;				// order of the controller (0-10 asc)
	
	static public $linksList = null;

	/**
	 * Request handler
	 * Personal Info on all pages. 
	 */
	public function subactionGetMenus($args) {
		Benchmark::log("Begin of creating MenuController::subactionGetMenus");
		$allPagesMenus = $this->loadCache('allPagesMenus');
		if (!$allPagesMenus) {
			$allPagesMenus = false;
			$menus = BasicMenuModel::Search('BasicMenuModel', array('all_pages = ?'), array(1));
			if ($menus) {
				$allPagesMenus = array();
				foreach ($menus as $menu) {
					$menuName = str_replace('-', '_', $menu->url);
					$menuItem = new BasicMenuItemsModel();
					$menuItem->addQualification('menu = ?', $menu->id);
					$menuItem->setOrder('rank');
					$allPagesMenus[$menuName] = $menuItem->getCollectionItems();
				}
			}
			$this->saveCache('allPagesMenus', $allPagesMenus, array('BasicMenuModel', 'BasicMenuItemsModel'));
		}
		$this->assign("allPagesMenus", $allPagesMenus);
		Benchmark::log("End of creating MenuController::subactionGetMenus");
	}

	
	public function getLinksList($controllerName=null) {
		if ($controllerName) {
			return self::$linksList[$controllerName];
		}
		return self::$linksList;
	}
	

	/**
	 * Request handler
	 */
	public function actionLinksList($args) {
		
		if (self::$linksList === null) {
			self::$linksList = array();
			$sitemap = new LinkCollection();
			$sitemap->getContentFromControllers('Sitemap');
			foreach ($sitemap->links as $link) {
				if (!isset(self::$linksList[$link->getActionPackage()])) {
					self::$linksList[$link->getActionPackage()] = array();
				}
				if (!isset(self::$linksList[$link->getActionPackage()][$link->getActionController()])) {
					self::$linksList[$link->getActionPackage()][$link->getActionController()] = array();
				}
				self::$linksList[$link->getActionPackage()][$link->getActionController()][] = array(
					'link' => $link->autolink,
					'title' => $link->title,
					);
			}
		}
		echo json_encode(self::$linksList);exit;
		$this->assign('linksList', json_encode(self::$linksList));
	}


}

?>