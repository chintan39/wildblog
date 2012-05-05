{include file='Base.part.header'}

	<div class="article">
	{if $article}
	<h1>{$article->title}</h1>
	{$article->text}
	{include package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
	{/if}
	</div>

{include file='Base.part.footer'}

