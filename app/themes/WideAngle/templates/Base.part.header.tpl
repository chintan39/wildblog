<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <meta name="description" content="{$seoDescription|default:$pageDescription|default:$projectDescription}" />
    <meta name="keywords" content="{$seoKeywords|default:$projectKeywords}" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="{if $noindex}noindex, nofollow{else}index, follow{/if}" />
    <meta name="rating" content="general" />
    <meta name="author" content="Honza Horák; mailto:horak.jan@centrum.cz" />
    <meta name="generator" content="{$appGenerator}" />
    <meta name="copyright" content="Honza Horák" />
    {include file='Base.part.rssFeeds.tpl'}
    <link rel="shortcut icon" type="image/x-icon" href="{$base}{if $useThemeFavicon}app/themes/{$generalTheme}/images/{else}media/{/if}favicon.ico" />
    <title>{$pageTitle|default:$title}</title>
    <!-- css_adding -->
    <link rel="stylesheet" href="{$base}app/themes/{$generalTheme}/css/skel.css" />
    <link rel="stylesheet" href="{$base}app/themes/{$generalTheme}/css/style.css" />
    <link rel="stylesheet" href="{$base}app/themes/{$generalTheme}/css/style-desktop.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="{$base}app/themes/{$generalTheme}/css/ie/v8.css" /><![endif]-->
    <!-- javascript_adding -->
    <!--[if lte IE 8]><script src="{$base}app/themes/{$generalTheme}/css/ie/html5shiv.js"></script><![endif]-->
    <script src="{Javascript::JQUERY_URL}"></script>
    <script src="{$base}app/themes/{$generalTheme}/js/jquery.dropotron.min.js"></script>
    <script src="{$base}app/themes/{$generalTheme}/js/skel.min.js"></script>
    <script src="{$base}app/themes/{$generalTheme}/js/skel-layers.min.js"></script>
    <script src="{$base}app/themes/{$generalTheme}/js/init.js"></script>
    <base href="{$base}" />
</head>
	<body class="homepage">

		<!-- Header Wrapper -->
			<div id="header-wrapper">
						
				<!-- Header -->
					<div id="header" class="container">

						<!-- Logo -->
							<h1 id="logo"><a href="{$base}"><span><img src="{$base}app/themes/{$generalTheme}/images/logo.png" /></span></a></h1>
						
						<!-- Nav -->
							<nav id="nav">
								<ul>
									<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem=6}">Mycí fáze</a></li>
									<li><a href="#">Webkamera</a></li>
									<li><a href="{linkto package=Gallery controller=Galleries action=actionGalleryDetail dataItem=1}">Galerie</a></li>
									<li class="break"><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem=7}">Ceník</a></li>
									<li><a href="{linkto package=Basic controller=News action=actionNewsList}">Akce</a></li>
									<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem=5}">Kontakt</a></li>
									<li><a href="">&nbsp;&nbsp;&nbsp;</a></li>
								</ul>
							</nav>

					</div>

			</div>

