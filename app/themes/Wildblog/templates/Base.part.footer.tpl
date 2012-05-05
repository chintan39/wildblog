	<!-- main content end -->
	
    </div><!-- content -->
      {include file='Base.part.navigation.tpl'}
    </div><!-- left -->
    <div id="right">
      {include file='Basic.part.footerArticle.tpl' package=Basic}
      {include file='Basic.part.articlesMenu.tpl' package=Basic}
      {include file='Base.part.advertisements.tpl' package=Basic}
      {*require file='Basic.part.tagsMenu.tpl' package=Basic*}
      {include file='Blog.part.relatedPosts.tpl' package=Blog}
      {include file='Blog.part.recentPosts.tpl' package=Blog}
      {*include file='Blog.part.favouritesLastWeek.tpl' package=Blog*}
      {include file='Blog.part.tagsMenu.tpl' package=Blog}
      {include file='Basic.part.htmlAreas.tpl' package=Basic}
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
    {include file='Base.part.searchForm.tpl'}
	</div><!-- header-search -->
    <div id="header-langs">
    {include file='Base.part.languages.tpl' languages=$frontendLanguages}
	</div><!-- header-langs -->
  </div><!-- header -->
</div><!-- upper -->
<div id="lower">
  <div id="footer">
    <div id="footer-left">
    {include package=Blog file='Blog.part.archive.tpl'}
    <h3>{tp}other{/tp}</h3>
	<div class="list">
	  <a href="{linkto controller=Sitemap action=actionSitemap}">{tg}Sitemap{/tg}</a>
	  <a href="{linkto package=Blog controller=Posts action=actionRss}">RSS 2.0</a>
    </div><!-- list -->
    </div><!-- footer-left -->
    <div id="footer-right">
	  {include package=LinkBuilding file='LinkBuilding.part.partnerLinks.tpl'}
	  {*include_package package=Blog file='Blog.part.postsFavouritesLastWeek.tpl'*}
	  {*include_package package=Blog file='Blog.part.postsMostDisscused.tpl'*}
    </div><!-- footer-right -->
	<div class="clear"></div>
	<div id="footer-bottom">
	</div>
  </div><!-- footer -->
  <div id="footer-copy">
	<div class="float-left">
	
        {include file='Base.part.wwFooter.tpl' sep=' '}
        
	</div><!-- left -->
	<div class="float-right">
		<a href="http://validator.w3.org/check?uri=referer" title="Tyto stránky jsou validní podle XHTML 1.0.">
		<img src="{$commonImagesPath}valid-xhtml10.gif" alt="Valid XHTML 1.0" /></a>
		<a href="http://jigsaw.w3.org/css-validator/" title="Tyto stránky jsou validní podle CSS2.">
		<img src="{$commonImagesPath}valid-css2.gif" alt="Valid CSS2" /></a>
		<a href="http://validator.w3.org/feed/check.cgi?uri=referer" title="Valid RSS 2.0.">
		<img src="{$commonImagesPath}valid-rss.gif" alt="Valid RSS 2.0" /></a>
	</div><!-- right -->
	<div class="clear"></div>
  </div><!-- footer-copy -->
</div><!-- lower -->
</div><!-- page -->

{include file='Base.part.adminBox.tpl' }

{include file='Base.part.footer.tpl'  ajaxLoaderSuffix=_threedots}
