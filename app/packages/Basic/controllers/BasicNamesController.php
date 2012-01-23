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


class BasicNamesController extends AbstractBasicController {
	
	/**
	 * Request handler
	 * Name days controller 
	 */
	public function subactionGetName($args) {
		Benchmark::log("Begin of creating BasicNamesController::subactionGetName");
		require_once(DIR_LIBS . 'namedays/NameDays.class.php');
		NameDays::init(Language::getCode());
		$todayName = NameDays::getName();
		$todayEvent = NameDays::getToleranceFlowerDays(14);
		$this->assign("todayName", $todayName);
		$this->assign("todayEvent", $todayEvent);
		Benchmark::log("End of creating BasicNamesController::subactionGetName");
	}


}

?>
