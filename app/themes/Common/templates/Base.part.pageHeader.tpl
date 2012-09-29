<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
  <head>
  <title>{$pageTitle|default:$title}</title>
  <meta name="description" content="{$seoDescription|default:$pageDescription|default:$projectDescription}" />
  <meta name="keywords" content="{$seoKeywords|default:$projectKeywords}" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="robots" content="{if $noindex}noindex, nofollow{else}index, follow{/if}" />
  <meta name="rating" content="general" />
  <meta name="author" content="Honza Horák; mailto:horak.jan@centrum.cz" />
  <meta name="generator" content="{$appGenerator}" />
  <meta name="copyright" content="Honza Horák" />
  <link rel="stylesheet" media="screen,projection" type="text/css" href="{$base}app/themes/Common/css/common.css" />
  <link rel="stylesheet" media="print" type="text/css" href="{$base}app/themes/{$generalTheme}/css/print.css" />
  <link rel="stylesheet" media="screen,projection" type="text/css" href="{$base}app/themes/{$generalTheme}/css/screen.css" />
  <!-- css_adding -->
  {include file='Base.part.rssFeeds.tpl'}
  <link rel="shortcut icon" type="image/x-icon" href="{$base}{if $useThemeFavicon}app/themes/{$generalTheme}/images/{else}media/{/if}favicon.ico" />
  <!-- javascript_adding -->
  <base href="{$base}" />
  </head>
  <body>

