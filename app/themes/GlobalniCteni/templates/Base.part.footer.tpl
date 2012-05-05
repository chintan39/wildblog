
</div><!-- contentpanel -->

<div id="leftpanel">
<h2>{tg}Menu{/tg}</h2>
{*require file='Basic.part.allPagesMenus.tpl' package=Basic menuName='top_menu' ulClass='sidemenu'*}
{include file='Basic.part.articlesMenu.tpl' package=Basic ulClass='sidemenu'}

<div class="newsletterlink">
<h2>Newsletter</h2>
<a href="{linkto package=Newsletter controller=Contacts action=actionRegister}">{tg}Newsletter register{/tg}</a>
</div>

<div class="news">
{include package=Basic file='Basic.part.recentNews.tpl'}
</div>

<div class="rsslink">
<a href="{linkto package=Basic controller=News action=actionRss}"><img src="{$iconsPath}64/rss.png" alt="RSS" title="RSS" /></a>
</div>


<div class="advertisments">
<p><a href="/userFiles/opvk_mu_stred_1_neg-vertikalni_4.jpg"><img title="" src="{$base}project/images/sponzori.jpg" alt="Sponzori" /></a></p>
</div><!-- advertisments -->

</div><!-- leftpanel -->
<span class="clear"></span><!-- clear flaoting -->
</div><!-- contentwrap -->

<div id="header">
<div id="logo">
<a href=""><img src="{$base}app/themes/GlobalniCteni/images/logo.gif" alt="HOME" /></a>
</div><!-- logo -->

<div id="header-search">
<form action="search/" method="get">
<fieldset>
<input type="text" name="s" class="vanish-onclick" value="hledat na webu..." />
<input type="submit" name="submit" class="submit" value="Hledej" />
<span class="clear"></span><!-- clear flaoting -->
</fieldset>
</form>
</div><!-- header-search -->

<div id="topmenu">
{include file='Basic.part.allPagesMenus.tpl' package=Basic menuName='top_menu' ulClass='topmenu'}

<span class="clear"></span><!-- clear flaoting -->
</div><!-- topmenu -->

</div><!-- header -->


<div id="footer">
<div class="upper">

{include file='Basic.part.footerArticle.tpl' package=Basic}

</div>
<div class="lower">

{include file='Basic.part.shortContact.tpl' package=Basic}

</div>
</div>
</div><!-- page -->

{include file='Basic.part.htmlAreas.tpl' package=Basic}

{include file='Base.part.adminBox.tpl' }

{include file='Base.part.footer.tpl' }
