{require file='part.header'}

	{if $article}
	{$article->text}
	{require package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemItem=$article}
	{/if}

{require file='part.footer'}

