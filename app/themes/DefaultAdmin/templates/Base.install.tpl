<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
  <head>
  <title>WildBlog installer</title>
  <meta name="description" content="Popis projektu" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="robots" content="index, follow" />
  <meta name="rating" content="general" />
  <meta name="author" content="Jan Horák; mailto:horak.jan@centrum.cz" />
  <meta name="generator" content="WildBlog version 0.1.debug" />
  <meta name="copyright" content="Jan Horák" />
  <link rel="stylesheet" media="screen,projection" type="text/css" href="{$base}app/themes/Common/css/install.css" />
  <link rel="shortcut icon" type="image/x-icon" href="{$base}app/themes/Common/images/favicon.ico" />
  </head>
  <body>

<div id="page">

<h1>WildBlog instal</h1>

{if $result}
<p class="confirm">Project has been installed <strong>successfully</strong>. You can login with the following email and password (password can be changed after login): 
<br /><br /><strong>email:</strong> {$email}<br /><strong>password:</strong> {$password}
<br /><br />You can login in <a href="{$base}admin/">{$base}admin/</a>.</p>
{else}
<ul class="errors">
<li>Project cannot be installed successfully because of problems in database:</li>
{foreach from=$errors item=item}
<li>{$item};</li>
{/foreach}
</ul>
{/if}

</div>
</body></html>

