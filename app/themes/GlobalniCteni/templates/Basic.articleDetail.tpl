{require file='part.header'}

	<div class="article">
	{if $article}
	{$article->text}
	{require package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemItem=$article}
	{/if}
	</div>

{require file='part.footer'}

