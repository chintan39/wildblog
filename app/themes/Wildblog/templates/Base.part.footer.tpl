	<!-- main content end -->
	
    </div><!-- content -->
      {require file='part.navigation'}
    </div><!-- left -->
    <div id="right">
      {require file='part.footerArticle' package=Basic}
      {require file='part.articlesMenu' package=Basic}
      {require file='part.advertisements' package=Basic}
      {*require file='part.tagsMenu' package=Basic*}
      {require file='part.relatedPosts' package=Blog}
      {require file='part.recentPosts' package=Blog}
      {*include file='part.favouritesLastWeek' package=Blog*}
      {require file='part.tagsMenu' package=Blog}
      {require file='part.htmlAreas' package=Basic}
    </div><!-- right -->
	<div class="clear"></div>
  </div><!-- middle -->
  <div id="header">
    <div id="header-logo">
    <a href="{$base}"><img src="app/themes/Wildblog/images/header-logo.gif" alt="" title="" />
	<span class="text">{$projectTitle}<br />&nbsp;&nbsp;<span>blog</span></span>
	</a>
    </div><!-- header-logo -->
    <div id="header-menu">
    	<a href="{linkto package=Blog controller=Posts action=actionPostsList}">home</a>
    	<a href="{linkto package=Blog controller=Posts action=actionPostsList}">blog</a>
    	<a href="http://picasaweb.google.cz/horak.honza/" rel="external">fotogalerie</a>
    	{*<a href="{linkto package=Basic controller=Articles action=actionDetail filters='url=?' values='about'}">about</a>*}
    </div><!-- header-menu -->
    <div id="header-quot">
    {tp}header top quot{/tp}
    </div><!-- header-quot -->
    <div id="header-rss">
	<a href="{linkto package=Blog controller=Posts action=actionRss}"><img src="app/themes/Wildblog/images/header-rss.gif" title="RSS Feed" alt="RSS Feed" /></a>
    </div><!-- header-rss -->
    <div id="header-search">
    {require file='part.searchForm'}
	</div><!-- header-search -->
    <div id="header-langs">
    {require file='part.languages' languages=$frontendLanguages}
	</div><!-- header-langs -->
  </div><!-- header -->
</div><!-- upper -->
<div id="lower">
  <div id="footer">
    <div id="footer-left">
    {require package=Blog file='part.archive'}
    <h3>{tp}other{/tp}</h3>
	<div class="list">
	  <a href="{linkto controller=Sitemap action=actionSitemap}">{tg}Sitemap{/tg}</a>
	  <a href="{linkto package=Blog controller=Posts action=actionRss}">RSS 2.0</a>
    </div><!-- list -->
    </div><!-- footer-left -->
    <div id="footer-right">
	  {require package=LinkBuilding file='part.partnerLinks'}
	  {*include_package package=Blog file='part.postsFavouritesLastWeek'*}
	  {*include_package package=Blog file='part.postsMostDisscused'*}
    </div><!-- footer-right -->
	<div class="clear"></div>
	<div id="footer-bottom">
	</div>
  </div><!-- footer -->
  <div id="footer-copy">
	<div class="float-left">
		Jan Horák, <a href="http://www.wild-web.eu" title="wild-web">wild-web.eu</a> &copy; 2009 
	</div><!-- left -->
	<div class="float-right">
		<a href="http://validator.w3.org/check?uri=referer" title="Tyto stránky jsou validní podle XHTML 1.0.">
		<img src="app/themes/Default/images/valid-xhtml10.gif" alt="Valid XHTML 1.0" /></a>
		<a href="http://jigsaw.w3.org/css-validator/" title="Tyto stránky jsou validní podle CSS2.">
		<img src="app/themes/Default/images/valid-css2.gif" alt="Valid CSS2" /></a>
		<a href="http://validator.w3.org/feed/check.cgi?uri=referer" title="Valid RSS 2.0.">
		<img src="app/themes/Default/images/valid-rss.gif" alt="Valid RSS 2.0" /></a>
	</div><!-- right -->
	<div class="clear"></div>
  </div><!-- footer-copy -->
</div><!-- lower -->
</div><!-- page -->

{require file='part.footer' theme=Common}
