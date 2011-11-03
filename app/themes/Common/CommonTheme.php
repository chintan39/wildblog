<?php

/**
 * Default front-end theme.
 */
class CommonTheme extends AbstractTheme {
	
	var $name = 'Common';
	
	var $templatesDependency = array(
		'Base.part.header' => array(
			'Common|Base.part.rssFeeds',
			),
		'Base.formEmail.html' => array(),
		'Base.part.cleanForm' => array(),
		'Base.formEmail' => array(),
		'Base.part.footer' => array(),
	);
		
}

?>
