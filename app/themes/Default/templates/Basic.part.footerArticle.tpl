{if $footerArticle}
{$footerArticle->text}
{include  file='Basic.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$footerArticle}
{/if}

