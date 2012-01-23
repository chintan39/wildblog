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
 * Blue back-end theme.
 */
class BlueAdminTheme extends AbstractTheme {
	
	var $name = 'BlueAdmin';
	
	var $templatesDependency = array(
		'Base.defaultEdit' => array(
			'Base.part.header',
			'Base.part.footer',
			'Base.part.cleanForm',
			),
		'Base.defaultView' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.defaultList' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.index' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.init' => array(),
		'Base.login' => array(
			'Base.part.header',
			'Base.part.footer',
			'Base.part.cleanForm',
			),
		'Base.notFound' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.part.footer' => array(
			'Base.part.menuLeft',
			'Base.part.languages',
			'Base.part.userInfo',
			'Base.part.languages',
			'Common|Base.part.footer',
			),
		'Base.part.header' => array(
			'Common|Base.part.footer',
			),
		'Base.part.languages' => array(),
		'Base.part.menuLeft' => array(),
		'Base.part.userInfo' => array(),
		);
		
}
?>
