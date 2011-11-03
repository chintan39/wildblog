<?php

/**
 * Default front-end theme.
 */
class BrunckoTheme extends AbstractTheme {
	
	var $name = 'Bruncko';
	
	var $templatesDependency = array(
		'Base.part.header' => array(
			'Common|Base.part.header',
		),
		'Base.part.footer' => array(
			'Common|Base.part.footer',
			'Basic.part.personalInfo',
			'Basic.part.shortContact',
			'Gallery.part.galleriesList',
			'Basic.part.htmlAreas',
			'References.part.references',
		),
		'Gallery.galleriesList' => array(
			'Base.part.header',
			'Base.part.footer',
		),
		'Gallery.galleryDetail' => array(
			'Base.part.header',
			'Base.part.footer',
		),
	);
		
}
?>
