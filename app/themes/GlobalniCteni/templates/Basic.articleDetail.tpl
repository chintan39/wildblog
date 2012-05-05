{include file='Base.part.header'}

	<div class="article">
	{if $article}
	{$article->text}
	{include  file='Basic.part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
	{/if}
	</div>

{include file='Base.part.footer'}

