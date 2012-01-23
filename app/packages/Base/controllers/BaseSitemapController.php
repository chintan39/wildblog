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