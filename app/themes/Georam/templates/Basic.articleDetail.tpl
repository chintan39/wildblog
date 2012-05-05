{include file='Base.part.header'}

<h1>{$article->title}</h1>

<div id="content-in">

{if $isHomepage and $config.BASIC_HOMEPAGE_SECTION_IDS and $config.BASIC_HOMEPAGE_SECTION_COUNT}
<!-- 3 sections -->
<div id="sections" class="box">
{section name=sec start=1 loop=$config.BASIC_HOMEPAGE_SECTION_COUNT+1}
<!-- Section -->
<div class="section sec{$smarty.section.sec.index}">
	{assign var=articleIds value=':'|explode:$config.BASIC_HOMEPAGE_SECTION_IDS}
	{assign var=secId value=$smarty.section.sec.index}
	{assign var=secIdm1 value=$secId-1}
	<h3><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem=$articleIds.$secIdm1 onempty='#'}">{tg}Homepage Section #{$secId}{/tg}</a></h3>
	<p><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem=$articleIds.$secIdm1 onempty='#'}"><img src="media/section{$secId}.jpg" width="280" height="175" alt="" /></a></p>
	<div class="section-in">
		<p>{tg}Homepage Section Text #{$secId}{/tg}</p>
	</div> <!-- /section-in -->
</div> <!-- /section -->
{/section}
</div> <!-- /sections -->
{/if}

{if $article}
{$article->text}
{include package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
{/if}

</div> <!-- /content-in -->

{include file='Base.part.footer'}

