{if not $widepage}
<hr class="invisible" />

</div><!-- contentpanel -->

<div id="rightpanel">

<div class="box">
<h2>Kdy a kde cvičím</h2>
<a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='18'}" class="cal"><br /><br />Klikněte zde pro zobrazení celého kalendáře s&nbsp;pravidelnými hodinami</a>

<hr class="invisible" />
</div> <!-- box -->

<div class="box">

<div class="partners">
{include file='LinkBuilding.part.partnerLinks.tpl'}
</div>

<hr class="invisible" />
</div> <!-- box -->


<div class="box">
<h2>Fotogalerie</h2>
<div id="gallery">
{include file='Gallery.part.galleriesList.tpl'}
</div>
<a href="{linkto package=Gallery controller=Galleries action=actionGalleriesList}" class="arrow">Zobrazit všechny fotky</a>

<hr class="invisible" />
</div> <!-- box -->

</div><!-- rightpanel -->
{else} {*wide page end*}
</div><!-- widecontentpanel -->
<div class="bottom"></div>
{/if} {*wide page end*}

<span class="clear"></span><!-- clear flaoting -->

</div><!-- contentwrap -->
<div id="headwrap">
<!--
<div id="header">
<h2><a href="{$base}">{tp}header top title{/tp}</a></h2>
<p>{tp}header top subtitle{/tp}</p>
</div>--><!-- header -->

<div id="topmenu">
<ul>
<li><a href="{linkto package=Basic controller=Articles action=actionHomepageArticle}">O mně</a></li>
<li><a href="{linkto package=Basic controller=News action=actionNewsPrimaryTag}">Aktuálně</a></li>
<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='11'}">Sportovní akce</a></li>
{*<li><a href="{linkto package=Basic controller=Tags action=actionNewsTagDetail dataItem='1'}">Jednodenní akce</a></li>
<li><a href="{linkto package=Basic controller=Tags action=actionNewsTagDetail dataItem='2'}">Víkendové pobyty</a></li>
<li><a href="{linkto package=Blog controller=Posts action=actionPostsList}">Blog</a></li>*}
<li><a href="{linkto package=FAQ controller=Questions action=actionQuestionsList}">Vzkazník</a></li>
{*<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='8'}">Kde se cvičí</a></li>*}
<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='18'}">Rozvrh</a></li>
{*<li><a href="#">Akce</a></li>*}
<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='17'}">Individiální lekce</a></li>
<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='9'}">Styly cvičení</a></li>
<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='1'}">Certifikáty</a></li>
<li><a href="{linkto package=Gallery controller=Galleries action=actionGalleriesList}">Fotogalerie</a></li>
<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='6'}"
>Ceník</a></li>
<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='5'}">Kontakt</a></li>
</ul>
</div><!-- topmenu -->

<div id="header-search">
<form action="{$base}search/" method="get">
<fieldset>
<input type="text" name="s" class="vanish-onclick" value="hledat na webu..." />
<input type="submit" name="submit" class="submit" value="Hledej" />
</fieldset>
</form>
</div><!-- header-search -->
</div><!-- headerwrap -->

<div class="footwrap">
<div class="footer-top"></div>
<div class="footer-middle">
<div class="footer-right">
{include file='Basic.part.personalInfo.tpl'}
</div>

<div class="footer-left">
{include file='Basic.part.shortContact.tpl'}
<a href="http://www.facebook.com/profile.php?id=100000013252832&ref=search" rel="external" class="facebook"><img src="{$iconsPath}64/facebook.png" alt="Facebook" /></a>
</div>

<div class="footer-left">
{include file='LinkBuilding.part.partnerLinks.tpl'}
</div>

<span class="clear"></span><!-- clear flaoting -->
</div><!-- footer-middle -->
<div class="footer-bottom"></div>
</div><!-- footwrap -->

<div class="footwrap second">
<div class="footer-top"></div>
<div class="footer-middle">
<div class="copy">

{include file='Base.part.wwFooter.tpl' sep=' '}

</div><!-- footer-middle -->
<div class="footer-bottom"></div>
</div><!-- footwrap -->

</div><!-- page -->

{include file='Basic.part.htmlAreas.tpl'}

{include file='Base.part.adminBox.tpl'}

{include file='Base.part.footer.tpl'}
