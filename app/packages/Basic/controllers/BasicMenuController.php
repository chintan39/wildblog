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
					$menuItem->addSorting('rank');
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