<?php

/**
 * Default front-end theme.
 */
class ReaperTheme extends AbstractTheme {
	
	var $name = 'Reaper';
	
	var $templatesDependency = array(
		'Commodity.referenceAdd' => array(
			'Base.part.header',
			'Common|Base.part.cleanForm',
			'Base.part.footer',
			),
		'Commodity.part.favourites' => array(),
		'Commodity.categoryDetail' => array(
			'Base.part.header',
			'Commodity.part.productList',
			'Basic.part.contactForm',
			'Base.part.footer',
			),
		'Commodity.references' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Commodity.productList' => array(
			'Base.part.header',
			'Commodity.part.productList',
			'Basic.part.contactForm',
			'Base.part.footer',
			),
		'Basic.part.homepageArticle' => array(),
		'Commodity.part.productList' => array(),
		'Basic.part.contactForm' => array(
			'Common|Base.part.cleanForm',
			),
		'Basic.articleDetail' => array(
			'Base.part.header',
			'Basic.part.contactForm',
			'Base.part.footer',
			),
		'Commodity.productDetail' => array(
			'Base.part.header',
			'Basic.part.contactForm',
			'Base.part.footer',
			),
		'Commodity.part.references' => array(),
		'Commodity.part.manofacturersList' => array(),
		'Base.part.footer' => array(
			'Commodity.part.references',
			'Commodity.part.actions',
			'Commodity.part.favourites',
			'Basic.part.advertisements',
			'Basic.part.htmlAreas',
			'Basic.part.articlesMenu',
			'Commodity.part.categoriesMenu',
			'Commodity.part.manofacturersList',
			'Base.part.navigation',
			'Base.part.searchForm',
			'Common|Base.part.footer',
			),
		'Basic.part.articlesMenu' => array(
			'Base.part.itemLinkTree',
			),
		'Base.part.header' => array(
			'Common|Base.part.header',
			'Basic.part.homepageArticle',
			),
		'Commodity.part.categoriesMenu' => array(
			'Base.part.itemLinkTree',
			),
		'Commodity.part.actions' => array(),
		'Commodity.manofacturersList' => array(
			'Base.part.header',
			'Base.part.footer',
			),
		'Commodity.manofacturerDetail' => array(
			'Base.part.header',
			'Commodity.part.productList',
			'Base.part.footer',
			),
		);
		
}
?>
