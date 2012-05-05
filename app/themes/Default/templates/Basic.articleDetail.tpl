{include file='Base.part.header.tpl'}

	<div class="article">
	{if $article}
	<h1>{$article->title}</h1>
	{$article->text}
	{include  file='Basic.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
	{/if}
	</div>

{include file='Base.part.footer.tpl'}

