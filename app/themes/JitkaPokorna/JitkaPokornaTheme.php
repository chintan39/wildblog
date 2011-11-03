<?php

/**
 * Default front-end theme.
 */
class JitkaPokornaTheme extends AbstractTheme {
	
	var $name = 'JitkaPokorna';
	
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
