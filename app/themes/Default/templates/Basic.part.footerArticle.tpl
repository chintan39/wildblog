{if $footerArticle}
{$footerArticle->text}
{include package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$footerArticle}
{/if}

