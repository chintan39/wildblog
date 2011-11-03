<?php

class BaseSitemapController extends AbstractBasicController {

	/**
	 * Sitemap action
	 */
	public function actionSitemap($args) {
		$this->generateSitemap($args);
		//$this->display('sitemap');
	}
	
	/**
	 * Sitelinks action
	 */
	public function actionAvailableLinks($args) {
		Request::setMimeType('application/x-javascript');
		$this->generateSitemap($args);
		//$this->display('sitemap');
	}
	
	/**
	 * Sitemap action
	 */
	public function actionSitemapXML($args) {
		$this->generateSitemap($args);
		//$this->display('sitemap.xml');
	}
	
	private function generateSitemap($args) {
		Benchmark::log('Begin of creating Sitemap::generateSitemap');
		$sitemap = new LinkCollection();
		$sitemap->getContentFromControllers('Sitemap');
		$sitemap->sort('link');
		$this->assign('sitemap', $sitemap->getLinks());
		Benchmark::log('End of creating Sitemap::generateSitemap');
	}

}

?>