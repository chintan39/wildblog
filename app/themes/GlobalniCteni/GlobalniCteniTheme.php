<?php

/**
 * Default front-end theme.
 */
class GlobalniCteniTheme extends AbstractTheme {
	
	var $name = 'GlobalniCteni';
	
	var $templatesDependency = array(
		'Base.part.header' => array(
			'Common|Base.part.header',
		),
		'Base.part.footer' => array(
			'Common|Base.part.footer',
			'Basic.part.shortContact',
			'Basic.part.htmlAreas',
			'Basic.part.allPagesMenus',
			'Basic.part.footerArticle',
			'Basic.part.recentNews',
			'Basic.part.articlesMenu',
			'GlobalReading.part.categoriesMenu',
			'References.part.references',
		),
		'GlobalReading.formCreate' => array(
			'Base.part.header', 
			'Base.part.footer',
			),
	);
		
}
?>
