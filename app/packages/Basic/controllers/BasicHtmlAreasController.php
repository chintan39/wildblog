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


class BasicHtmlAreasController extends AbstractPagesController {
	
	public $order = 7;				// order of the controller (0-10 asc)
	
	/**
	 * Request handler
	 * Articles structure generation. 
	 */
	public function subactionMainHtmlAreas($args) {
		Benchmark::log("Begin of creating AdvertisementsController::subactionMainHtmlAreas");
		$mainAreas = $this->loadCache('mainAreas');
		if (!$mainAreas) {
			$mainAreas = new ItemCollection("mainAreas", $this);
			$mainAreas->loadCollection();
			$this->saveCache('mainAreas', $mainAreas, array('BasicHtmlAreasModel'));
		}
		$this->assign($mainAreas->getIdentifier(), $mainAreas);
		Benchmark::log("End of creating AdvertisementsController::subactionMainHtmlAreas");
	}

}

?>