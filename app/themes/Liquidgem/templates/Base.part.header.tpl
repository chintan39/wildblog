{*<!DOCTYPE HTML>
<!-- ............................. -->
<!-- ............................. -->
<!-- ..... LIQUID GEM V.1.0. ..... -->
<!-- ............................. -->
<!-- ............................. -->

<!-- ............................. -->
<!-- ............................. -->
<!-- .... MADE BY CHRIS BIRON .... -->
<!-- ............................. -->
<!-- ............................. -->

<!-- ............................. -->
<!-- ............................. -->
<!-- Liquid Gem is licensed under 
          Creative Commons 
 Attribution-NonCommercial-ShareAlike
       3.0 Unported License -->
<!-- ............................. -->
<!-- ............................. -->
<html lang="cs">
<head>
    <meta charset="utf-8">
    <meta name="description" content="{$seoDescription|default:$pageDescription|default:$projectDescription}" />
    <meta name="keywords" content="{$seoKeywords|default:$projectKeywords}" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="robots" content="{if $noindex}noindex, nofollow{else}index, follow{/if}" />
    <meta name="rating" content="general" />
    <meta name="author" content="Honza Horák; mailto:horak.jan@centrum.cz" />
    <meta name="generator" content="{$appGenerator}" />
    <meta name="copyright" content="Honza Horák" />
    {include file='Base.part.rssFeeds.tpl'}
    <link rel="stylesheet" media="screen,projection" type="text/css" href="{$base}app/themes/Common/css/common.css" />
    <link rel="shortcut icon" type="image/x-icon" href="{$base}{if $useThemeFavicon}app/themes/{$generalTheme}/images/{else}media/{/if}favicon.ico" />
    <title>{$pageTitle|default:$title}</title>
    <!-- css_adding -->
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700|Cookie' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="{$base}app/themes/{$generalTheme}/css/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="{$base}app/themes/{$generalTheme}/js/jquery.carouFredSel-5.5.2.js" type="text/javascript"></script>
<script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/jquery.form.js"></script> 
<script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/scripts.js"></script> 
    <script src="{Javascript::JQUERY_URL}"></script>
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
    <base href="{$base}" />
</head>
<body>
*}
{include file='Base.part.pageHeader.tpl' }

<div class="wrapper">
	<div id="top">
        <div id="logo">
            <img id="logoimage" src="media/ml-logo-medium.png" alt="logo">	<!-- Logo image -->
            <h1 id="logotitle"><a href="{$base}">{tp}Project Title{/tp}</a></h1>	<!-- Logo text -->
        </div><!--/logo-->
    
        <nav>	<!-- Navigation Start -->
{if $allPagesMenus and $allPagesMenus.main_menu and $allPagesMenus.main_menu->links}
<ul>
	{foreach from=$allPagesMenus.main_menu->links item=item}
		<li class="{$item->activity}{if $item->subLinks} li-with-ul{/if}"><a href="{$item->link}">{$item->title}</a>
		{if $item->subLinks}
            <ul>
			{foreach from=$item->subLinks item=item2}
				<li class="{$item2->activity}"><a href="{$item2->link}">{$item2->title}</a></li>
			{/foreach}
            </ul>
		{/if}
		</li>
	{/foreach}
</ul>
{/if}

        </nav>	<!-- Navigation End -->
    </div><!--/top-->
    
    
    <hr/><!-- Horizontal Line -->
    



