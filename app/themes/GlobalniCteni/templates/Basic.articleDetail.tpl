{include file='part.header'}

	<div class="article">
	{if $article}
	{$article->text}
	{include package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}
	{/if}
	</div>

{include file='part.footer'}

