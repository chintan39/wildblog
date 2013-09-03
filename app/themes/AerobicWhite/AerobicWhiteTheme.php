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
class AerobicWhiteTheme extends AbstractTheme {
	
	var $name = 'AerobicWhite';
	
	var $templatesDependency = array(
		'Attendance.eventDetail' => array(
			'Base.part.header',
			'Base.part.footer',
			'Basic.part.recentNews',
			'LinkBuilding.part.partnerLinks',
		),
		'Base.part.header' => array(
			'Common|Base.part.header',
			'Basic.part.allPagesMenus',
		),
		'Base.part.footer' => array(
			'Common|Base.part.footer',
			'Basic.part.htmlAreas',
		),
		'Basic.articleDetail' => array(
			'Base.part.header',
			'Base.part.footer',
			'Basic.part.recentNews',
			'Gallery.part.galleriesList',
			'LinkBuilding.part.partnerLinks',
		),
		'Basic.newsDetail' => array(
			'Base.part.header',
			'Base.part.footer',
			'Gallery.part.galleriesList',
			'LinkBuilding.part.partnerLinks',
		),
		'Basic.newsList' => array(
			'Base.part.header',
			'Base.part.footer',
			'Gallery.part.galleriesList',
			'LinkBuilding.part.partnerLinks',
		),
		'Commodity.productDetail' => array(
			'Base.part.header',
			'Base.part.footer',
			'Basic.part.recentNews',
			'LinkBuilding.part.partnerLinks',
		),
		'Commodity.productList' => array(
			'Base.part.header',
			'Base.part.footer',
			'Gallery.part.galleriesList',
			'LinkBuilding.part.partnerLinks',
		),
		'Gallery.galleryDetail' => array(
			'Base.part.header',
			'Base.part.footer',
			'Basic.part.recentNews',
			'LinkBuilding.part.partnerLinks',
		),
		'Gallery.galleriesList' => array(
			'Base.part.header',
			'Base.part.footer',
			'Basic.part.recentNews',
			'LinkBuilding.part.partnerLinks',
		),
	);
		
}
?>
