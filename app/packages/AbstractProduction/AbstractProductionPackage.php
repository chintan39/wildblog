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
 * Package covering link building actions and tools.
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class AbstractProductionPackage extends DefaultAbstractPackage {
    	
	public function setDefaultConfig() {
		
		Config::Set("ABSTRACTPRODUCTION_PACKAGE_ORDER", 10, null, Config::INT, true);
		Config::Set("ABSTRACTPRODUCTION_PACKAGE_LANGUAGE_SUPPORT", false, null, Config::BOOL, false);
		Config::Set("ABSTRACTPRODUCTION_PACKAGE_ALLOW", true, null, Config::BOOL, false);
	
	}

}

?>