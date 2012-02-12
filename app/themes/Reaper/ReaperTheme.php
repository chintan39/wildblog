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
class ReaperTheme extends AbstractTheme {
	
	var $name = 'Reaper';
	
	var $templatesDependency = array(
		'Commodity.productDetail' => array(
			'Base.part.header',
			'Basic.part.contactForm',
			'Base.part.footer',
			),
		'Commodity.categoryDetail' => array(
			'Base.part.header',
			'Commodity.part.productList',
			'Basic.part.contactForm',
			'Base.part.footer',
			),
		'Commodity.productList' => array(
			'Base.part.header',
			'Commodity.part.productList',
			'Basic.part.contactForm',
			'Base.part.footer',
			),
		'Commodity.productDetail' => array(
			'Base.part.header',
			'Basic.part.contactForm',
			'Base.part.footer',
			),
		'Basic.part.homepageArticle' => array(),
		'Basic.part.contactForm' => array(
			'Common|Base.part.cleanForm',
			),
		'Basic.articleDetail' => array(
			'Base.part.header',
			'Basic.part.contactForm',
			'Base.part.footer',
			),
		'Base.part.footer' => array(
			'Commodity.part.references',
			'Commodity.part.actions',
			'Commodity.part.favourites',
			'Basic.part.advertisements',
			'Basic.part.htmlAreas',
			'Basic.part.articlesMenu',
			'Commodity.part.categoriesMenu',
			'Commodity.part.manofacturersList',
			'Base.part.navigation',
			'Base.part.searchForm',
			'Common|Base.part.footer',
			),
		'Basic.part.articlesMenu' => array(
			'Base.part.itemLinkTree',
			),
		'Base.part.header' => array(
			'Common|Base.part.header',
			'Basic.part.homepageArticle',
			),
		);
		
}
?>
