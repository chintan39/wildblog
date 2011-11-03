
</div><!-- contentpanel -->

<div id="leftpanel">
<h2>{tg}Menu{/tg}</h2>
{*require file='part.allPagesMenus' package=Basic menuName='top_menu' ulClass='sidemenu'*}
{require file='part.articlesMenu' package=Basic ulClass='sidemenu'}

<div class="newsletterlink">
<h2>Newsletter</h2>
<a href="{linkto package=Newsletter controller=Contacts action=actionRegister}">{tg}Newsletter register{/tg}</a>
</div>

<div class="news">
{require package=Basic file='part.recentNews'}
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
{require file='part.allPagesMenus' package=Basic menuName='top_menu' ulClass='topmenu'}

<span class="clear"></span><!-- clear flaoting -->
</div><!-- topmenu -->

</div><!-- header -->


<div id="footer">
<div class="upper">

{require file='part.footerArticle' package=Basic}

</div>
<div class="lower">

{require file='part.shortContact' package=Basic}

</div>
</div>
</div><!-- page -->


{require file='part.htmlAreas' package=Basic}

{require file='part.footer' theme=Common}

