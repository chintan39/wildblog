<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{foreach from=$sitemap item=link}
	<url>
		<loc>{$link->link}</loc>
	</url>
{foreach from=$link->subLinks item=subLink}
	<url>
		<loc>{$subLink->link}</loc>
	</url>
{/foreach}
{/foreach}
</urlset>
