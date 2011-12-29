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
			//'Gallery.part.galleriesList',
			//'LinkBuilding.part.partnerLinks',
			//'References.part.references',
		),
	);
		
}
?>