{include file='Base.part.header.tpl'}

	<div class="article">
	{if $article}
	{$article->text}
	{include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
	{/if}
	</div>

{include file='Base.part.footer.tpl'}

