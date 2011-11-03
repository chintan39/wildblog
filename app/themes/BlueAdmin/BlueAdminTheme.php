<?php

/**
 * Blue back-end theme.
 */
class BlueAdminTheme extends AbstractTheme {
	
	var $name = 'BlueAdmin';
	
	var $templatesDependency = array(
		'Base.defaultEdit' => array(
			'Base.part.header',
			'Base.part.footer',
			'Base.part.cleanForm',
			),
		'Base.defaultView' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.defaultList' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.index' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.init' => array(),
		'Base.login' => array(
			'Base.part.header',
			'Base.part.footer',
			'Base.part.cleanForm',
			),
		'Base.notFound' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.part.footer' => array(
			'Base.part.menuLeft',
			'Base.part.languages',
			'Base.part.userInfo',
			'Base.part.languages',
			'Common|Base.part.footer',
			),
		'Base.part.header' => array(
			'Common|Base.part.footer',
			),
		'Base.part.languages' => array(),
		'Base.part.menuLeft' => array(),
		'Base.part.userInfo' => array(),
		);
		
}
?>
