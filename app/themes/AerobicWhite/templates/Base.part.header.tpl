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
    <link rel="stylesheet" type="text/css" media="screen" href="{$base}app/themes/{$generalTheme}/css/reset.css">
    <link rel="stylesheet" type="text/css" media="screen" href="{$base}app/themes/{$generalTheme}/css/style.css">
    <link rel="stylesheet" type="text/css" media="screen" href="{$base}app/themes/{$generalTheme}/css/grid_16.css">
    <link rel="stylesheet" type="text/css" media="screen" href="{$base}app/themes/{$generalTheme}/css/superfish.css">
    <link rel="stylesheet" type="text/css" media="screen" href="{$base}app/themes/{$generalTheme}/css/prettyPhoto.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <link href='http://fonts.googleapis.com/css?family=Marck+Script&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <!-- javascript_adding -->
    {*<script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/jquery.atooltip.min.js"></script>
    <script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/superfish.js"></script>
    <script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/scrollTop.js"></script>
    <script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="{$base}app/themes/{$generalTheme}/js/FF-cash.js"></script>*}
    <script type="text/javascript">
		$(function(){ldelim}
			$('a.normalTip').aToolTip();
		{rdelim}); 
		$(function(){ldelim}
			$('.lightbox-image').prettyPhoto({ldelim}theme:'facebook',autoplay_slideshow:false,social_tools:false,animation_speed:'normal'{rdelim}).append('<span></span>').hover(function(){ldelim}$(this).find('>img').stop().animate({ldelim}opacity:.5{rdelim}){rdelim},function(){ldelim}$(this).find('>img').stop().animate({ldelim}opacity:1{rdelim}){rdelim})	  
		{rdelim})
	</script>
	<!--[if lt IE 8]>
       <div style=' clear: both; text-align:center; position: relative;'>
         <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
           <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
        </a>
      </div>
    <![endif]-->
    <!--[if lt IE 9]>
   		<script type="text/javascript" src="js/html5.js"></script>
    	<link rel="stylesheet" type="text/css" media="screen" href="css/ie.css">
	<![endif]-->
    <base href="{$base}" />
</head>
<body>

<!--==============================header=================================-->
      <header>
      	<nav>
{strip}
{if $allPagesMenus and $allPagesMenus.left_menu and $allPagesMenus.left_menu->links}
<ul class="sf-menu">
	{foreach from=$allPagesMenus.left_menu->links item=item}
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
{/strip}
{*            <ul class="sf-menu">
                <li><a href="#">O mě</a></li>
                <li class="current"><a href="#">Aktuálně</a></li>
                <li class="li-with-ul"><a href="#">Styly cvičení</a>
                    <ul>
                        <li><a href="#">Pilates</a></li>
                        <li><a href="#">Zdravotní cvičení</a></li>
                        <li><a href="#">Dynamická jóga</a></li>
                        <li><a href="#">Individuální lekce</a></li>
                    </ul>
                </li>
                <li class="li-with-ul"><a href="#">Rozvrh hodin</a>
                    <ul>
                        <li><a href="#">Rozvrh na léto 2013</a></li>
                        <li><a href="#">Bystrc</a></li>
                        <li><a href="#">Židlochovice</a></li>
                        <li><a href="#">AC Fitnes</a></li>
                        <li><a href="#">MAT-FIT</a></li>
                    </ul>
                </li>
                <li><a href="#">Kosmetika</a></li>
                <li><a href="#">Prodej pomůcek</a></li>
                <li><a href="#">Ceník</a></li>
                <li><a href="#">Kontakt</a></li>
            </ul>    
*}
            <div class="clear"></div>   
        </nav>
        <div class="main-banner">
          <h1><a href="{$base}"><img src="{$base}app/themes/{$generalTheme}/images/logo.png" alt=""></a></h1>
        </div>  
      </header>
<!--==============================content================================-->
