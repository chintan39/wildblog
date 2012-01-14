<?php

/**
 * Default front-end theme.
 */
class DefaultTheme extends AbstractTheme {
	
	var $name = 'Default';
	
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
			'Basic.part.htmlAreas',
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
		'LinkBuilding.tagsPartners' => array(
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
