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
 * Default front-end theme.
 */
class DefaultAdminTheme extends AbstractTheme {
	
	var $name = 'DefaultAdmin';
	
	var $templatesDependency = array(
		'Base.defaultEdit' => array(
			'Base.part.header',
			'Base.part.footer',
			'Base.part.cleanForm',
			),
		'Base.defaultSimpleEdit' => array(
			'Common.part.header',
			'Common.part.footer',
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
		'Base.dbcheck' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.testResult' => array(
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
			'Common|Base.part.pageFooter',
			),
		'Base.part.header' => array(
			'Common|Base.part.pageHeader',
			'Base.part.menuTop',
			),
		'Base.part.languages' => array(),
		'Base.part.menuLeft' => array(),
		'Base.part.userInfo' => array(),
		'Gallery.simpleImages' => array(
			'Base.part.header',
			'Base.part.footer',
			'Base.part.cleanForm',
			),
		);
		
}
?>
