{require file='part.header'}

{if $article}
{$article->text}
{require package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemItem=$article}
{/if}

{if $isHomepage}
{require package=Basic file='part.recentNews'}
{/if}

{require file='part.footer'}

