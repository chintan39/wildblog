<?php

/**
 * Default front-end theme.
 */
class DefaultAdminTheme extends AbstractTheme {
	
	var $name = 'DefaultAdmin';
	
	var $templatesDependency = array(
		'Base.defaultEdit' => array(
			'Base.part.header',
			'Base.part.footer',
			'Base.part.cleanForm',
			),
		'Base.defaultSimpleEdit' => array(
			'Common.part.header',
			'Common.part.footer',
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
		'Base.dbcheck' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Base.testResult' => array(
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
			'Base.part.menuTop',
			),
		'Base.part.languages' => array(),
		'Base.part.menuLeft' => array(),
		'Base.part.userInfo' => array(),
		);
		
}
?>
