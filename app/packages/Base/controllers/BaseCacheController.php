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


class BaseCacheController extends AbstractBasicController {

	
	public function getIcon() {
		return 'page';
	}
	
	public function actionClearCache($args) {
		$this->clearCache(DIR_SMARTY_TEMPLATES_C);
		$this->clearCache(DIR_MODELS_CACHE);
		$this->clearCache(DIR_CONTROLLERS_CACHE);
		$this->assign('message', tg('Cache has been removed.'));
	}

	private function clearCache($dir) {
		$files = scandir($dir);
		foreach ($files as $file) {
			if (is_dir($dir . $file)) {
				if ($file[0] != '.') {
					$this->clearHashes($dir . $file . '/');
				}
			} elseif (is_file($dir . $file)) {
				unlink($dir . $file);
			}
		}
	}

	
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, 'actionClearCache'), 
			'label' => tg('Clear cache'), 
			'title' => tg('removes cached data, stored templates'), 
			'image' => $this->getIcon(), 
			'action' => array(
				'package' => $this->package, 
				'controller' => $this->name, 
				'action' => 'actionClearCache')));
		$link->setOrder($this->order);
		return array($link);
	}

}

?>