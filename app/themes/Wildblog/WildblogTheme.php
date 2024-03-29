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
 * Wildblog front-end theme.
 */
class WildblogTheme extends AbstractTheme {
	
	var $name = 'Wildblog';
	
	var $templatesDependency = array(
		'Base.index' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Base.default' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Base.notFound' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Base.part.addNewItem' => array(),
		'Base.part.editItem' => array(),
		'Base.part.footer' => array(
			'Base.part.navigation',
			'Basic.part.personalInfo',
			'Basic.part.articlesMenu',
			'Basic.part.advertisements',
			'Blog.part.relatedPosts',
			'Blog.part.recentPosts',
			'Blog.part.tagsMenu',
			'Basic.part.htmlAreas',
			'Basic.part.tagsMenu',
			'Base.part.searchForm',
			'Base.part.languages',
			'Blog.part.archive',
			'LinkBuilding.part.partnerLinks',
			'Common|Base.part.footer',
			),
		'Base.part.header' => array(
			'Common|Base.part.header',
			),
		'Base.part.itemLinkTree' => array(),
		'Base.part.languages' => array(),
		'Base.part.navigation' => array(),
		'Base.part.rss.footer' => array(),
		'Base.part.rss.header' => array(),
		'Base.part.searchForm' => array(),
		'Base.rss' => array(
			'Base.part.rss.header',
			'Base.part.rss.footer',
			),
		'Base.search' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Base.sitelinks' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Base.sitemap' => array(
			'Base.part.header', 
			'Base.part.footer',
			'Base.part.itemLinkTree',
			),
		'Base.sitemap.xml' => array(),
		'Basic.articleDetail' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Basic.newsDetail' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Basic.newsList' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Basic.contactForm' => array(
			'Base.part.header', 
			'Base.part.footer',
		),
		'Basic.part.advertisements' => array(),
		'Basic.part.articlesMenu' => array(
			'Base.part.itemLinkTree'
			),
		'Basic.part.htmlAreas' => array(),
		'Basic.part.personalnfo' => array(),
		'Basic.part.recentNews' => array(),
		'Basic.part.shortContact' => array(),
		'Basic.part.tagsMenu' => array(),
		'Blog.blogList' => array(
			'Base.part.header', 
			'Base.part.footer',
			'Blog.part.tags',
			),
		'Blog.blogPostDetail' => array(
			'Base.part.header', 
			'Base.part.footer',
			'Blog.part.tags',
			'Blog.part.relatedPostsDown',
			'Blog.part.comments',
			'Common|Base.part.editItem',
			),
		'Blog.blogTagDetail' => array(
			'Base.part.header', 
			'Base.part.footer',
			'Blog.part.tags',
			),
		'Blog.part.archive' => array(),
		'Blog.part.comments' => array(
			'Common|Base.part.cleanForm',
			),
		'Blog.part.favouritesLastWeek' => array(),
		'Blog.part.postsMostDisscused' => array(),
		'Blog.part.recentPosts' => array(),
		'Blog.part.relatedPosts' => array(),
		'Blog.part.relatedPostsDown' => array(),
		'Blog.part.tags' => array(),
		'Blog.part.tagsMenu' => array(),
		'Blog.rss' => array(
			'Base.rss',
			),
		'FAQ.questionAdd' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'FAQ.questions' => array(
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
		'Gallery.part.galleriesList' => array(),
		'LinkBuilding.part.partnerLinks' => array(),
		'LinkBuilding.partners' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Newsletter.register' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Newsletter.getToken' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Newsletter.check' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Newsletter.send' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'References.part.references' => array(),
		'References.referenceAdd' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'References.references' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		'Research.researchDetail' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
		);
		
}
?>
