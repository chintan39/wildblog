<?php

/**
 * Default front-end theme.
 */
class JunglelandTheme extends AbstractTheme {
	
	var $name = 'Jungleland';
	
	var $templatesDependency = array(
		'Base.part.header' => array(
			'Common|Base.part.header',
		),
		'Base.part.footer' => array(
			'Common|Base.part.footer',
			'Basic.part.shortContact',
			'Basic.part.htmlAreas',
			'Basic.part.footerArticle',
			'Basic.part.recentNews',
			'Basic.part.articlesMenu',
			'Basic.part.personalInfo',
			'Basic.part.allPagesMenus',
			'Basic.part.nameDays',
			'Basic.part.recentNews',
			//'Gallery.part.galleriesList',
			//'LinkBuilding.part.partnerLinks',
			//'References.part.references',
		),
		'Basic.articleDetail' => array(
			'Basic.part.recentNews',
			'Basic.part.contactForm',
			'Base.part.header', 
			'Base.part.footer',
		),
		'Gallery.galleriesList' => array(
			'References.part.references',
			'Base.part.header', 
			'Base.part.footer',
		),
		'Gallery.galleryDetail' => array(
			'References.part.references',
			'Base.part.header', 
			'Base.part.footer',
		),
	);
		
}
?>