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
class AerobicOrangeTheme extends AbstractTheme {
	
	var $name = 'AerobicOrange';
	
	var $templatesDependency = array(
		'Base.part.header' => array(
			'Common|Base.part.header',
		),
		'Base.part.footer' => array(
			'Common|Base.part.footer',
			'Basic.part.personalInfo',
			'Basic.part.shortContact',
			'LinkBuilding.part.partnerLinks',
			'Gallery.part.galleriesList',
			'Basic.part.htmlAreas',
			'Basic.part.recentNews',
		),
		'FAQ.questions' => array(
			'Base.part.header',
			'Base.part.footer',
		),
		'FAQ.questionAdd' => array(
			'Base.part.header',
			'Base.part.footer',
		),
		'Gallery.galleriesList' => array(
			'Base.part.header',
			'Base.part.footer',
		),
		'Gallery.galleryDetail' => array(
			'Base.part.header',
			'Base.part.footer',
		),
		'Basic.newsList' => array(
			'Base.part.header',
			'Base.part.footer',
		),
		'Basic.newsDetail' => array(
			'Base.part.header',
			'Base.part.footer',
		),
	);
		
}
?>
