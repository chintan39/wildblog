<?php

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