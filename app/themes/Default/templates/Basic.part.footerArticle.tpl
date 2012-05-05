{if $footerArticle}
{$footerArticle->text}
{include  file='Basic.part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$footerArticle}
{/if}

