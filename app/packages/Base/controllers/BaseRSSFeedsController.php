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


/**
 * Handles RSS feeds export to the HTML.
 */
class BaseRSSFeedsController extends AbstractBasicController {
	
	public function subactionRSSFeeds($args) {
		$results = $this->loadCache('rssFeeds');
		if (!$results) {
			$results = array();
			foreach (Environment::getPackages() as $package) {
				$package->loadControllers();
				// Try to get links from all controllers in the package
				foreach ($package->getControllers() as $controller) {
					if (method_exists($controller, 'getRSSFeed')) {
						$results = array_merge($results, $controller->getRSSFeed());
					}
				}
			}
			$this->saveCache('rssFeeds', $results, array('Static'), true);
		}
		
		$this->assign("rssFeeds", $results);
	}
	

}

?>
